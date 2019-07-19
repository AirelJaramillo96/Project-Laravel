<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable =[
        'idcategory','code','name','price_vent','stock','description','condition'
    ];
    public function category(){
        return $this->belongsTo('App\Category');
    }
}
