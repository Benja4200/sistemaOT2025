<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactoOt extends Model
{
    use SoftDeletes;

    protected $table = 'contacto_ot';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'cod_contacto',
        'cod_ot',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function contacto()
    {
        return $this->belongsTo(Contacto::class, 'cod_contacto', 'id');
    }

    public function ot()
    {
        return $this->belongsTo(Ot::class, 'cod_ot', 'numero_ot');
    }
}
