<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TecnicoServicio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tecnico_servicio';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'cod_tecnico',
        'cod_servicio',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Define the relationship
    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class, 'cod_tecnico');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'cod_servicio');
    }
}
