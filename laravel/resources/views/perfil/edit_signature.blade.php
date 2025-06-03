@extends('layouts.master')

@section('content')
 <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">Editar Firma</h5>
                </div>
             
                <div class="card-body">
                    <form id="signature-form" method="POST" action="{{ route('signature.update', $user->id) }}">
                        @csrf
                        @method('PUT') <div class="text-center">
                            <canvas id="signature-pad" width="800" height="300" style="border: 1px solid #ccc;"></canvas>
                        </div>
                        <div class="mt-3 text-center">
                            <button type="button" id="clear" class="btn btn-outline-secondary">Limpiar</button>
                            <button type="button" id="save" class="btn btn-primary">Guardar</button>
                        </div>
                        <input type="hidden" name="signature" id="signature" value="{{ $user->firma }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);

        // Guarda la firma existente para recargarla después del redimensionamiento
        let existingSignatureDataURL = "{{ $user->firma }}";

        function resizeCanvas() {
            const parentWidth = canvas.parentElement.clientWidth;
            const parentHeight = 300; // Puedes ajustar esta altura si lo necesitas

            const ratio = Math.max(window.devicePixelRatio || 1, 1);

            // Establecer las dimensiones intrínsecas del canvas para pantallas de alta DPI
            canvas.width = parentWidth * ratio;
            canvas.height = parentHeight * ratio;

            // Establecer las dimensiones de visualización del canvas usando CSS
            canvas.style.width = parentWidth + 'px';
            canvas.style.height = parentHeight + 'px';

            // Escalar el contexto para que coincida con el devicePixelRatio para un dibujo claro
            // Esto es crucial para que el trazo coincida con la posición del ratón
            canvas.getContext('2d').scale(ratio, ratio);

            // Recargar la firma si existe después de redimensionar
            if (existingSignatureDataURL) {
                // Es importante esperar a que el canvas se haya redimensionado antes de cargar la firma
                // Podría haber un pequeño delay, pero generalmente se resuelve en el mismo ciclo de evento
                signaturePad.fromDataURL(existingSignatureDataURL);
            } else {
                signaturePad.clear(); // Limpiar si no hay firma o si se redimensiona sin contenido previo
            }
        }

        // Llamar a la función de ajuste de tamaño al cargar la página
        resizeCanvas();
        // Ajustar el tamaño al redimensionar la ventana
        window.addEventListener('resize', resizeCanvas); 

        document.getElementById('clear').addEventListener('click', function () {
            signaturePad.clear();
            existingSignatureDataURL = ''; // Limpiar la data URL también si se borra
        });

        document.getElementById('save').addEventListener('click', function () {
            if (signaturePad.isEmpty()) {
                alert("Por favor, firme antes de guardar.");
            } else {
                const dataURL = signaturePad.toDataURL("image/png"); // Puedes especificar el formato y calidad
                document.getElementById('signature').value = dataURL;
                document.getElementById('signature-form').submit();
            }
        });
    });
</script>
@endsection