<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialPrecios extends Model
{
    use HasFactory;
    protected $table = 'historial_precios';
    public $timestamps = false;
}
