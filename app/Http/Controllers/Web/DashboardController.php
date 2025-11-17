<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Client;
use App\Models\Movement;
use App\Models\ReservationHeader;
use App\Models\SaleHeader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->get('period', '30'); // Default: last 30 days
        $startDate = now()->subDays((int) $period);

        // Key metrics
        $metrics = [
            'total_books' => Book::count(),
            'low_stock_books' => Book::where('stock_actual', '<=', 3)->count(),
            'total_clients' => Client::count(),
            'active_reservations' => ReservationHeader::whereIn('estado', ['Reservado', 'Parcial'])->count(),
            'total_sales' => SaleHeader::where('fecha_venta', '>=', $startDate)->count(),
            'sales_revenue' => SaleHeader::where('fecha_venta', '>=', $startDate)->sum('total'),
            'pending_returns' => ReservationHeader::whereIn('estado', ['Reservado', 'Parcial'])->count(),
        ];

        // Top 10 most reserved/sold books
        $topBooks = Book::select('books.*')
            ->withCount(['reservationDetails as reservations_count', 'saleDetails as sales_count'])
            ->orderByDesc(DB::raw('reservations_count + sales_count'))
            ->limit(10)
            ->get();

        // Top 10 clients by activity
        $topClients = Client::select('clients.*')
            ->withCount(['reservations', 'sales'])
            ->orderByDesc(DB::raw('reservations_count + sales_count'))
            ->limit(10)
            ->get();

        // Low stock alerts
        $lowStockBooks = Book::with('category')
            ->where('stock_actual', '<=', 3)
            ->orderBy('stock_actual')
            ->limit(15)
            ->get();

        // Sales over time (last 30 days)
        $salesChart = SaleHeader::selectRaw('DATE(fecha_venta) as date, COUNT(*) as count, SUM(total) as revenue')
            ->where('fecha_venta', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Reservations by status
        $reservationsByStatus = ReservationHeader::selectRaw('estado, COUNT(*) as count')
            ->groupBy('estado')
            ->get()
            ->pluck('count', 'estado');

        // Sales by category
        $salesByCategory = Category::select('categories.nombre')
            ->selectRaw('COUNT(DISTINCT sale_details.id_detalle_venta) as total_sales')
            ->selectRaw('SUM(sale_details.subtotal) as total_revenue')
            ->join('books', 'books.id_categoria', '=', 'categories.id_categoria')
            ->join('sale_details', 'sale_details.id_libro', '=', 'books.id_libro')
            ->join('sale_headers', 'sale_headers.id_venta', '=', 'sale_details.id_venta')
            ->where('sale_headers.fecha_venta', '>=', $startDate)
            ->groupBy('categories.id_categoria', 'categories.nombre')
            ->orderByDesc('total_revenue')
            ->get();

        // Recent movements
        $recentMovements = Movement::with(['client', 'book'])
            ->latest('fecha_movimiento')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'metrics',
            'topBooks',
            'topClients',
            'lowStockBooks',
            'salesChart',
            'reservationsByStatus',
            'salesByCategory',
            'recentMovements',
            'period'
        ));
    }
}
