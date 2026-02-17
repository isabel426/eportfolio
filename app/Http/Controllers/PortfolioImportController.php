<?php

namespace App\Http\Controllers;

use App\Services\ResumeImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PortfolioImportController extends Controller
{
    protected ResumeImportService $importService;

    public function __construct(ResumeImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Mostrar formulario de importación
     */
    public function showImportForm()
    {
        return view('portfolio.import');
    }

    /**
     * Importar desde JSON Resume
     */
    public function importJsonResume(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'json_resume_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $this->importService->importFromJsonResume(
            $request->input('json_resume_url')
        );

        if (!$data) {
            return back()->with('error', 'No se pudo importar el JSON Resume. Verifica la URL y el formato.');
        }

        // Aquí deberías guardar los datos en tu base de datos
        // Este es un ejemplo simplificado
        try {
            DB::beginTransaction();

            // Guardar información personal
            // Nota: Adapta esto a tu modelo de datos
            $user = Auth::user();
            $user->update([
                'name' => $data['personal_info']['name'],
                'email' => $data['personal_info']['email'],
                // ... otros campos
            ]);

            // Guardar experiencia laboral, educación, habilidades, etc.
            // Aquí usarías tus modelos Eloquent

            DB::commit();

            return redirect()
                ->route('portfolio.import.index')
                ->with('success', 'Portfolio importado correctamente desde JSON Resume');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error al guardar los datos: ' . $e->getMessage());
        }
    }

    /**
     * Importar desde GitHub
     */
    public function importGitHub(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'github_username' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $this->importService->importFromGitHub(
            $request->input('github_username')
        );

        if (!$data) {
            return back()->with('error', 'No se pudo importar desde GitHub. Verifica el usuario.');
        }

        try {
            DB::beginTransaction();

            // Guardar proyectos de GitHub
            // Adapta esto a tu modelo de datos
            foreach ($data['projects'] as $projectData) {
                // crea una evidencia por cada proyecto
                // asociada a la tarea con id = 1
                // pertenenciente al usuario autenticado
                // y con estado "pendiente"
            }

            DB::commit();

            return redirect()
                ->route('portfolio.import.index')
                ->with('success', "Se importaron " . count($data['projects']) . " proyectos desde GitHub");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error al guardar proyectos: ' . $e->getMessage());
        }
    }
}
