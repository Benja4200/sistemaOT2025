<form action="{{ route('repuestos.destroy', $repuesto->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este repuesto?')">
        <i class="fas fa-trash-alt"></i> Eliminar
    </button>
</form>
