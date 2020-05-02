<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //nombre de la tabla del modelo
	protected $table = 'items';
	//llave primaria de la tabla
	protected $primaryKey = 'barcode';
	//datos que pueden ser editados en las consultas
    protected $fillable = ['name','category','price','discount','quantity'];

}
