<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $fillable = [
        'id','contact','phone_contact'
    ];

    public $timestamps = false;

    public function person()
    {
        return $this->belongsTo('App\Persona');
    }
    //
}
