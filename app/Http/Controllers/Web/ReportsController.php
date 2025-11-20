<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Category;
use App\Services\ReportGenerator;
use App\Services\ExcelExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportsController extends Controller
{
    protected $reportGenerator;
    protected $excelExporter;

    public function __construct(ReportGenerator $reportGenerator, ExcelExporter $excelExporter)
    {
        $this->reportGenerator = $reportGenerator;
        $this->excelExporter = $excelExporter;
    }

    public function index()
    {
        $reportes = Report::orderBy('fecha_generacion', 'desc')->paginate(15);
        
        return view('reports.index', [
            'reportes' => $reportes,
            'tipos' => Report::TIPOS,
        ]);
    }

    public function create()
    {
        $categorias = Category::pluck('nombre', 'id_categoria');
        
        return view('reports.create', [
            'tipos' => Report::TIPOS,
            'formatos' => Report::FORMATOS,
            'categorias' => $categorias,
        ]);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'tipo' => 'required|in:' . implode(',', array_keys(Report::TIPOS)),
            'formato' => 'required|in:pdf,excel',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'categoria' => 'nullable|exists:categories,id_categoria',
            'estado' => 'nullable|string',
            'stock_bajo' => 'nullable|boolean',
            'tipo_movimiento' => 'nullable|string',
        ]);

        try {
            // Preparar parámetros
            $parametros = [
                'fecha_inicio' => $validated['fecha_inicio'] ? Carbon::parse($validated['fecha_inicio'])->startOfDay() : null,
                'fecha_fin' => $validated['fecha_fin'] ? Carbon::parse($validated['fecha_fin'])->endOfDay() : null,
                'categoria' => $validated['categoria'] ?? null,
                'estado' => $validated['estado'] ?? null,
                'stock_bajo' => $validated['stock_bajo'] ?? false,
                'tipo_movimiento' => $validated['tipo_movimiento'] ?? null,
            ];

            // Generar datos del reporte
            $data = $this->reportGenerator->generate($validated['tipo'], $parametros);

            // Exportar según formato
            if ($validated['formato'] === 'pdf') {
                $archivoPath = $this->generatePdf($data, $validated['tipo'], $validated['nombre']);
            } else {
                $archivoPath = $this->excelExporter->export($data, $validated['tipo']);
            }

            // Guardar registro del reporte
            $reporte = Report::create([
                'nombre' => $validated['nombre'],
                'tipo' => $validated['tipo'],
                'formato' => $validated['formato'],
                'parametros' => $parametros,
                'archivo_path' => $archivoPath,
                'generado_por' => 'Sistema',
                'fecha_generacion' => now(),
            ]);

            return redirect()
                ->route('web.reports.download', $reporte)
                ->with('status', 'Reporte generado exitosamente');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('warning', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    public function download(Report $report)
    {
        if (!$report->archivo_path || !file_exists($report->archivo_path)) {
            abort(404, 'Archivo no encontrado');
        }

        $extension = $report->formato === 'pdf' ? 'pdf' : 'csv';
        $filename = str_replace(' ', '_', $report->nombre) . '.' . $extension;

        return response()->download($report->archivo_path, $filename);
    }

    public function destroy(Report $report)
    {
        if ($report->archivo_path && file_exists($report->archivo_path)) {
            unlink($report->archivo_path);
        }

        $report->delete();

        return redirect()
            ->route('web.reports.index')
            ->with('status', 'Reporte eliminado correctamente');
    }

    protected function generatePdf(array $data, string $tipo, string $nombre): string
    {
        $pdf = Pdf::loadView('reports.pdf.' . $tipo, [
            'data' => $data,
            'nombre' => $nombre,
            'fecha_generacion' => now(),
        ]);

        $filename = storage_path('app/public/reports/' . uniqid('report_') . '.pdf');
        
        if (!file_exists(dirname($filename))) {
            mkdir(dirname($filename), 0755, true);
        }

        $pdf->save($filename);

        return $filename;
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:' . implode(',', array_keys(Report::TIPOS)),
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'categoria' => 'nullable|exists:categories,id_categoria',
            'estado' => 'nullable|string',
            'stock_bajo' => 'nullable|boolean',
            'tipo_movimiento' => 'nullable|string',
        ]);

        $parametros = [
            'fecha_inicio' => $validated['fecha_inicio'] ? Carbon::parse($validated['fecha_inicio'])->startOfDay() : null,
            'fecha_fin' => $validated['fecha_fin'] ? Carbon::parse($validated['fecha_fin'])->endOfDay() : null,
            'categoria' => $validated['categoria'] ?? null,
            'estado' => $validated['estado'] ?? null,
            'stock_bajo' => $validated['stock_bajo'] ?? false,
            'tipo_movimiento' => $validated['tipo_movimiento'] ?? null,
        ];

        $data = $this->reportGenerator->generate($validated['tipo'], $parametros);

        return view('reports.preview', [
            'data' => $data,
            'tipo' => $validated['tipo'],
            'tipoNombre' => Report::TIPOS[$validated['tipo']],
        ]);
    }
}
