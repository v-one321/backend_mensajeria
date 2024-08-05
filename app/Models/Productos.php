<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;
    public function categoria(){
        return $this->belongsTo(Categorias::class, 'categoria_id');
    }
}
