<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
   protected $fillable = [
    'idsupplier',
    'iduser',
    'type_voucher',
    'serie_voucher',
    'num_voucher',
    'date_hour',
    'tax',
    'total',
    'state'
   ];
   public function user()
       {
           return $this->belongsTo('\App\User');
       }
    public function supplier()
        {
        return $this->belongsTo('\App\Supplier');
        }   
}
