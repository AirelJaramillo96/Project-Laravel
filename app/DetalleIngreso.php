<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $table = 'detalle_ingresos';
    protected $fillable = [
        'identry',
        'idarticle',
        'quantity',
        'price'
    ];
    public $timestamps = false;
}
