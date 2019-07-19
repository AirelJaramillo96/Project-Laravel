<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['name','type_document','num_document','address','phone','email'];
    //
}
