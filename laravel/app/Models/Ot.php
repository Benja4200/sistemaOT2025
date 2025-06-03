<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ot extends Model
{
    use SoftDeletes;

    protected $table = 'ot';
    protected $primaryKey = 'numero_ot';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'horas_ot',
        'descripcion_ot',
        'comentario_ot',
        'cotizacion',
        'cod_tipo_ot',
        'cod_prioridad_ot',
        'cod_estado_ot',
        'cod_tipo_visita',
        'cod_servicio',
        'cod_contacto',
        'cod_tecnico_encargado',
        'cod_ejecutivo',
        'fecha_fin_planificada_ot',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'fecha_fin_planificada_ot',
    ];

    // Relación con la tabla `Contacto`
    public function contacto()
    {
        return $this->belongsTo(Contacto::class, 'cod_contacto', 'id');
    }
    
    public function ejecutivo()
    {
        return $this->belongsTo(Usuario::class, 'cod_ejecutivo', 'id');
    }

    // Relación con la tabla `Servicio`
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'cod_servicio', 'id');
    }

    // Relación con la tabla `Tecnico`
    public function tecnicoEncargado()
    {
        return $this->belongsTo(Tecnico::class, 'cod_tecnico_encargado', 'id');
    }

    // Relación con la tabla `EstadoOt`
    public function estado()
    {
        return $this->belongsTo(EstadoOt::class, 'cod_estado_ot', 'id');
    }

    // Relación con la tabla `TipoVisita`
    public function tipoVisita()
    {
        return $this->belongsTo(TipoVisita::class, 'cod_tipo_visita', 'id');
    }

    // Relación con la tabla `PrioridadOt`
    public function prioridad()
    {
        return $this->belongsTo(PrioridadOt::class, 'cod_prioridad_ot', 'id');
    }

    // Relación con la tabla `TipoOt`
    public function tipo()
    {
        return $this->belongsTo(TipoOt::class, 'cod_tipo_ot', 'id');
    }
    // Relación con la tabla `ContactoOt`
    public function contactoOt()
    {
        return $this->hasMany(ContactoOt::class, 'cod_ot', 'numero_ot');
    }

    public function EquipoTecnico()
    {
        return $this->hasMany(EquipoTecnico::class, 'cod_ot', 'numero_ot');
    }

    public function DispositivoOT()
    {
        return $this->hasMany(DispositivoOt::class, 'cod_ot', 'numero_ot');
    }

    public function TareasOt()
    {
        return $this->hasMany(TareaOt::class, 'cod_ot', 'numero_ot');
    }
    // Ot.php

    public function avances()
    {
        return $this->hasMany(Avance::class, 'cod_ot', 'numero_ot');
    }
    
    public function actividadesExtra()
    {
        return $this->hasMany(ActividadExtra::class, 'cod_ot', 'numero_ot');
    }
}
