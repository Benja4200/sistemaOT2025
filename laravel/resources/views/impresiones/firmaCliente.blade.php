@extends('layouts.master')

@section('content')
<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">Firma del cliente</h5>
                    </div>
                    <div class="card-body">
                        <form id="firmaClienteForm-form" method="POST" action="{{ route('guardarFirmaCliente') }}">
                            @csrf
                            <!-- Valor de OT para saber la orden a la que corresponde -->
                            <input type="hidden" name="ot_id" value="{{ $orden->numero_ot }}">
                            <!-- Input que recogerá la firma desde SignaturePad -->
                            <input type="hidden" name="signature" id="signature">
                            
                            <div class="text-center">
                                <canvas id="signature-pad" width="800" height="300" style="border: 1px solid #ccc;"></canvas>
                            </div>
                            <div class="mt-3 text-center">
                                <button type="button" id="clear" class="btn btn-outline-secondary">Limpiar</button>
                                <button type="button" id="save" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluimos la librería SignaturePad -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('signature-pad');
            // Inicializar SignaturePad sobre el canvas
            const signaturePad = new SignaturePad(canvas);

            function resizeCanvas() {
                const parentWidth = canvas.parentElement.clientWidth;
                const parentHeight = 300; // Puedes ajustar esta altura si lo necesitas

                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                // Ajusta las dimensiones intrínsecas
                canvas.width = parentWidth * ratio;
                canvas.height = parentHeight * ratio;
                canvas.style.width = parentWidth + 'px';
                canvas.style.height = parentHeight + 'px';

                // Escala el contexto para alta resolución
                canvas.getContext('2d').scale(ratio, ratio);
            }

            // Ajusta el tamaño del canvas al cargar y al redimensionar la ventana
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            // Evento para limpiar el canvas
            document.getElementById('clear').addEventListener('click', function () {
                signaturePad.clear();
            });

            // Evento para guardar la firma y enviar el formulario
            document.getElementById('save').addEventListener('click', function () {
                if (signaturePad.isEmpty()) {
                    alert("Por favor, firme antes de guardar.");
                } else {
                    // Convertir la firma a DataURL (imagen en base64)
                    const dataURL = signaturePad.toDataURL("image/png");
                    document.getElementById('signature').value = dataURL;
                    // Ahora se envía el formulario
                    document.getElementById('firmaClienteForm-form').submit();
                }
            });
        });
    </script>
@endsection
