<?php

namespace App\Http\Controllers\ControladorImpresiones;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ot;

class impresionController extends Controller
{
    public function imprimirOt($id,$estadoFirma)
    {
        $usuario = Auth::user();
        $firmaSc = $usuario->firma;
        $firmaCliente = session('firmaCliente', null);
        $informe = Ot::with([
            'avances',
            'contacto',
            'servicio',
            'tecnicoEncargado',
            'estado',
            'prioridad',
            'tipo',
            'tipoVisita',
            'contactoOt'
        ])->findOrFail($id);
       
        
        $customPaper = array(0, 0, 612.00, 1008.0);
        //dd($informe->DispositivoOT[0]->dispositivo);
        
        $combinedHtml = null;
        if(count($informe->DispositivoOT) > 0){
            $dispositivos = $informe->DispositivoOT->chunk(3);
            
            foreach ($dispositivos as $grupo)
            {
                $html1 = view('impresiones.ot-impresion', [
                    'informe' => $informe,
                    'grupoDispositivos' => $grupo,
                    'firmaSc' => ($estadoFirma === 'firmado') ? $firmaSc : null,
                    'firmaCliente' => $firmaCliente
                ])->render();
                
                $combinedHtml .= $html1;
            }
        }
        
        if(count($informe->TareasOt) > 0){
            $tareas = $informe->TareasOt->chunk(25);
            
            foreach ($tareas as $grupo)
            {
                $html1 = view('impresiones.ot-impresion',[
                    'informe' => $informe,
                    'grupoTareas' => $grupo,
                    'firmaSc' => ($estadoFirma === 'firmado') ? $firmaSc : null,
                    'firmaCliente' => $firmaCliente
                ])->render();
                
                $combinedHtml .= $html1;
            }
        }
        // Cargar la primera vista
        //$html1 = view('impresiones.ot-impresion', [
        //    'informe' => $informe
        //])->render();

        // Inicializar la variable para el HTML combinado
        //$combinedHtml = $html1;

        // Verificar si hay avances relacionados
        if ($informe->avances->isNotEmpty()) {
            // Dividir los avances en grupos de 10
            $avances = $informe->avances->chunk(7); // Divide en grupos de 10

            foreach ($avances as $grupo) {
                // Cargar la vista de avances para cada grupo
                $html2 = view('impresiones.ot-impresion2', [
                    'informe' => $informe,
                    'grupoAvances' => $grupo,
                    'firmaSc' => ($estadoFirma === 'firmado') ? $firmaSc : null,
                    'firmaCliente' => $firmaCliente
                ])->render();

                // Combinar ambas vistas en un solo HTML
                $combinedHtml .= $html2; // Usar .= para agregar la segunda página
            }
        }
        
        if ($informe->actividadesExtra->isNotEmpty()) {
            // Dividir los avances en grupos de 10
            $actividadesExtra = $informe->actividadesExtra->chunk(7); // Divide en grupos de 10

            foreach ($actividadesExtra as $grupo) {
                // Cargar la vista de avances para cada grupo
                $html2 = view('impresiones.ot-impresion3', [
                    'informe' => $informe,
                    'grupoActividades' => $grupo,
                    'firmaSc' => ($estadoFirma === 'firmado') ? $firmaSc : null,
                    'firmaCliente' => $firmaCliente
                ])->render();

                // Combinar ambas vistas en un solo HTML
                $combinedHtml .= $html2; // Usar .= para agregar la segunda página
            }
        }
        // Crear una instancia de PDF
        $pdf = PDF::loadHTML($combinedHtml)->setPaper($customPaper);

        // Generar el PDF
        return $pdf->stream('OrdenDeTrabajo.pdf');
    }
    
    public function vistaFirmaCliente($id)
    {
        $orden = Ot::findOrFail($id); // Busca la orden de trabajo
        return view('impresiones.firmaCliente', compact('orden')); // Carga la vista con la información de la OT
    }


    public function guardarFirmaCliente(Request $request)
    {
        
        // Validamos que se envíe la firma
        $request->validate([
            'signature' => 'required',
            'ot_id'    => 'required|exists:ot,numero_ot',
        ]);
    
        $firmaCliente = $request->signature;
        $id = $request->ot_id;
    
        // Redirige a la ruta de impresión y "fija" la firma en la sesión
        return redirect()->route('imprimirOt', ['id' => $id, 'estadoFirma' => 'SinFirmar'])
                         ->with('firmaCliente', $firmaCliente);
    }


}