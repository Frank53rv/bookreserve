<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Client;
use App\Models\SaleHeader;
use App\Models\ReservationHeader;
use App\Models\Movement;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportGenerator
{
    public function generate(string $tipo, array $parametros = [])
    {
        return match ($tipo) {
            'ventas' => $this->reporteVentas($parametros),
            'inventario' => $this->reporteInventario($parametros),
            'clientes' => $this->reporteClientes($parametros),
            'reservas' => $this->reporteReservas($parametros),
            'financiero' => $this->reporteFinanciero($parametros),
            'movimientos' => $this->reporteMovimientos($parametros),
            default => throw new \InvalidArgumentException("Tipo de reporte no válido: {$tipo}"),
        };
    }

    protected function reporteVentas(array $params)
    {
        $fechaInicio = $params['fecha_inicio'] ?? Carbon::now()->subMonth()->startOfDay();
        $fechaFin = $params['fecha_fin'] ?? Carbon::now()->endOfDay();
        $estado = $params['estado'] ?? null;

        $query = SaleHeader::with(['client', 'details.book'])
            ->whereBetween('fecha_venta', [$fechaInicio, $fechaFin]);

        if ($estado) {
            $query->where('estado', $estado);
        }

        $ventas = $query->get();

        $totales = [
            'total_ventas' => $ventas->count(),
            'monto_total' => $ventas->sum('total'),
            'promedio_venta' => $ventas->avg('total'),
            'libros_vendidos' => $ventas->sum(fn($v) => $v->details->sum('cantidad')),
        ];

        $ventasPorDia = $ventas->groupBy(function($venta) {
            return $venta->fecha_venta->format('Y-m-d');
        })->map(function($ventasDia) {
            return [
                'cantidad' => $ventasDia->count(),
                'monto' => $ventasDia->sum('total'),
            ];
        });

        $topLibros = DB::table('sale_details')
            ->join('sales', 'sale_details.id_venta', '=', 'sales.id_venta')
            ->join('books', 'sale_details.id_libro', '=', 'books.id_libro')
            ->whereBetween('sales.fecha_venta', [$fechaInicio, $fechaFin])
            ->select('books.titulo', 
                DB::raw('SUM(sale_details.cantidad) as cantidad_vendida'),
                DB::raw('SUM(sale_details.subtotal) as ingresos'))
            ->groupBy('books.id_libro', 'books.titulo')
            ->orderByDesc('cantidad_vendida')
            ->limit(10)
            ->get();

        return [
            'tipo' => 'ventas',
            'periodo' => ['desde' => $fechaInicio, 'hasta' => $fechaFin],
            'ventas' => $ventas,
            'totales' => $totales,
            'ventas_por_dia' => $ventasPorDia,
            'top_libros' => $topLibros,
        ];
    }

    protected function reporteInventario(array $params)
    {
        $categoria = $params['categoria'] ?? null;
        $stockBajo = $params['stock_bajo'] ?? false;
        $estado = $params['estado'] ?? null;

        $query = Book::with(['category', 'editorial']);

        if ($categoria) {
            $query->where('id_categoria', $categoria);
        }

        if ($stockBajo) {
            $query->where('stock_actual', '<=', 3);
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        $libros = $query->get();

        $totales = [
            'total_libros' => $libros->count(),
            'stock_total' => $libros->sum('stock_actual'),
            'valor_inventario' => $libros->sum(function($libro) {
                return $libro->stock_actual * ($libro->precio_venta ?? 0);
            }),
            'stock_bajo_count' => $libros->where('stock_actual', '<=', 3)->count(),
            'agotados' => $libros->where('stock_actual', 0)->count(),
        ];

        $porCategoria = $libros->groupBy('category.nombre')->map(function($librosCat) {
            return [
                'cantidad' => $librosCat->count(),
                'stock' => $librosCat->sum('stock_actual'),
                'valor' => $librosCat->sum(fn($l) => $l->stock_actual * ($l->precio_venta ?? 0)),
            ];
        });

        return [
            'tipo' => 'inventario',
            'libros' => $libros,
            'totales' => $totales,
            'por_categoria' => $porCategoria,
        ];
    }

    protected function reporteClientes(array $params)
    {
        $fechaInicio = $params['fecha_inicio'] ?? Carbon::now()->subMonth()->startOfDay();
        $fechaFin = $params['fecha_fin'] ?? Carbon::now()->endOfDay();

        $clientes = Client::withCount([
            'reservationHeaders as reservas_count',
            'sales as compras_count',
        ])
        ->with(['reservationHeaders' => function($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('fecha_reserva', [$fechaInicio, $fechaFin]);
        }])
        ->get();

        $totales = [
            'total_clientes' => $clientes->count(),
            'clientes_activos' => $clientes->where('reservas_count', '>', 0)->count(),
            'total_reservas' => $clientes->sum('reservas_count'),
            'total_compras' => $clientes->sum('compras_count'),
        ];

        $topClientes = $clientes->sortByDesc('compras_count')->take(10);

        return [
            'tipo' => 'clientes',
            'periodo' => ['desde' => $fechaInicio, 'hasta' => $fechaFin],
            'clientes' => $clientes,
            'totales' => $totales,
            'top_clientes' => $topClientes,
        ];
    }

    protected function reporteReservas(array $params)
    {
        $fechaInicio = $params['fecha_inicio'] ?? Carbon::now()->subMonth()->startOfDay();
        $fechaFin = $params['fecha_fin'] ?? Carbon::now()->endOfDay();
        $estado = $params['estado'] ?? null;

        $query = ReservationHeader::with(['client', 'details.book'])
            ->whereBetween('fecha_reserva', [$fechaInicio, $fechaFin]);

        if ($estado) {
            $query->where('estado', $estado);
        }

        $reservas = $query->get();

        $totales = [
            'total_reservas' => $reservas->count(),
            'libros_reservados' => $reservas->sum(fn($r) => $r->details->sum('cantidad')),
            'por_estado' => $reservas->groupBy('estado')->map->count(),
        ];

        $librosMasReservados = DB::table('reservation_details')
            ->join('reservation_headers', 'reservation_details.id_reserva', '=', 'reservation_headers.id_reserva')
            ->join('books', 'reservation_details.id_libro', '=', 'books.id_libro')
            ->whereBetween('reservation_headers.fecha_reserva', [$fechaInicio, $fechaFin])
            ->select('books.titulo', DB::raw('SUM(reservation_details.cantidad) as veces_reservado'))
            ->groupBy('books.id_libro', 'books.titulo')
            ->orderByDesc('veces_reservado')
            ->limit(10)
            ->get();

        return [
            'tipo' => 'reservas',
            'periodo' => ['desde' => $fechaInicio, 'hasta' => $fechaFin],
            'reservas' => $reservas,
            'totales' => $totales,
            'libros_mas_reservados' => $librosMasReservados,
        ];
    }

    protected function reporteFinanciero(array $params)
    {
        $fechaInicio = $params['fecha_inicio'] ?? Carbon::now()->subMonth()->startOfDay();
        $fechaFin = $params['fecha_fin'] ?? Carbon::now()->endOfDay();

        $ventas = SaleHeader::whereBetween('fecha_venta', [$fechaInicio, $fechaFin])->get();
        
        $ingresos = [
            'total_ventas' => $ventas->sum('total'),
            'ventas_count' => $ventas->count(),
            'promedio_diario' => $ventas->sum('total') / max(1, $fechaInicio->diffInDays($fechaFin)),
        ];

        $ventasPorMetodoPago = $ventas->groupBy('metodo_pago')->map(function($ventasMp) {
            return [
                'cantidad' => $ventasMp->count(),
                'monto' => $ventasMp->sum('total'),
            ];
        });

        $ventasPorCategoria = DB::table('sale_details')
            ->join('sales', 'sale_details.id_venta', '=', 'sales.id_venta')
            ->join('books', 'sale_details.id_libro', '=', 'books.id_libro')
            ->join('categories', 'books.id_categoria', '=', 'categories.id_categoria')
            ->whereBetween('sales.fecha_venta', [$fechaInicio, $fechaFin])
            ->select('categories.nombre', DB::raw('SUM(sale_details.subtotal) as ingresos'))
            ->groupBy('categories.id_categoria', 'categories.nombre')
            ->orderByDesc('ingresos')
            ->get();

        return [
            'tipo' => 'financiero',
            'periodo' => ['desde' => $fechaInicio, 'hasta' => $fechaFin],
            'ingresos' => $ingresos,
            'por_metodo_pago' => $ventasPorMetodoPago,
            'por_categoria' => $ventasPorCategoria,
        ];
    }

    protected function reporteMovimientos(array $params)
    {
        $fechaInicio = $params['fecha_inicio'] ?? Carbon::now()->subMonth()->startOfDay();
        $fechaFin = $params['fecha_fin'] ?? Carbon::now()->endOfDay();
        $tipo = $params['tipo_movimiento'] ?? null;

        $query = Movement::with(['book'])
            ->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);

        if ($tipo) {
            $query->where('tipo_movimiento', $tipo);
        }

        $movimientos = $query->get();

        $totales = [
            'total_movimientos' => $movimientos->count(),
            'por_tipo' => $movimientos->groupBy('tipo_movimiento')->map->count(),
            'total_cantidad' => $movimientos->sum('cantidad'),
        ];

        $librosMasMovimiento = $movimientos->groupBy('id_libro')->map(function($movs) {
            $libro = $movs->first()->book;
            return [
                'libro' => $libro?->titulo ?? 'Desconocido',
                'movimientos' => $movs->count(),
                'cantidad_total' => $movs->sum('cantidad'),
            ];
        })->sortByDesc('movimientos')->take(10);

        return [
            'tipo' => 'movimientos',
            'periodo' => ['desde' => $fechaInicio, 'hasta' => $fechaFin],
            'movimientos' => $movimientos,
            'totales' => $totales,
            'libros_mas_movimiento' => $librosMasMovimiento,
        ];
    }
}
