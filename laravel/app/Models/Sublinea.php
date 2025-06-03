<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sublinea extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sublinea';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nombre_sublinea',
        'cod_linea',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function linea()
    {
        return $this->belongsTo(Linea::class, 'cod_linea');
    }
}
