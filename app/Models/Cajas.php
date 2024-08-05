<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cajas extends Model
{
    use HasFactory;
    public function persona(){
        return $this->belongsTo(Personas::class, 'persona_id');
    }
    public function usuario(){
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
