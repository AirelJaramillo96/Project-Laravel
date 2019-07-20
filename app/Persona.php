<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['name','type_document','num_document','address','phone','email'];

    public function supplier()
    {
        return $this->hasOne('App\Supplier');
    }
    //
    public function user(){
        return $this->hasOne('App\User');
    }
}
