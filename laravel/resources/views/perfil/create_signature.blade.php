@extends('layouts.master')

@section('content')
 <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">Crear Firma</h5>
                </div>
             
                <div class="card-body">
                    <form id="signature-form" method="POST" action="{{ route('signature.store', $user->id) }}">
                        @csrf
                        <div class="text-center">
                            <canvas id="signature-pad" width="800" height="300" style="border: 1px solid #ccc;"></canvas>
                        </div>
                        <div class="mt-3 text-center">
                            <button type="button" id="clear" class="btn btn-outline-secondary">Limpiar</button>
                            <button type="button" id="save" class="btn btn-primary">Guardar</button>
                        </div>
                        <input type="hidden" name="signature" id="signature">
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

        function resizeCanvas() {
            // Get the bounding rectangle of the canvas's parent to determine available space.
            // This makes the canvas responsive to its container.
            const parentWidth = canvas.parentElement.clientWidth;
            const parentHeight = 300; // You can make this dynamic if needed, or keep a fixed height

            const ratio = Math.max(window.devicePixelRatio || 1, 1);

            // Set the canvas's intrinsic dimensions for high-DPI screens
            canvas.width = parentWidth * ratio;
            canvas.height = parentHeight * ratio;

            // Set the canvas's display dimensions using CSS
            canvas.style.width = parentWidth + 'px';
            canvas.style.height = parentHeight + 'px';

            // Scale the context to match the devicePixelRatio for clear drawing
            canvas.getContext('2d').scale(ratio, ratio);

            // Important: Redraw the signature after resizing if there was any content
            // If you want to keep the signature, you'd save it, clear, resize, then load.
            // For a fresh canvas on resize, clear is fine.
            signaturePad.clear(); 
        }

        // Initial resize and add event listener for window resize
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        document.getElementById('clear').addEventListener('click', function () {
            signaturePad.clear();
        });

        document.getElementById('save').addEventListener('click', function () {
            if (signaturePad.isEmpty()) {
                alert("Por favor, firme antes de guardar.");
            } else {
                // When getting the data URL, you might want to specify the image quality
                // and if you want to use the scaled canvas content.
                const dataURL = signaturePad.toDataURL("image/png"); // "image/jpeg" can also be used
                document.getElementById('signature').value = dataURL;
                document.getElementById('signature-form').submit();
            }
        });
    });
</script>
@endsection