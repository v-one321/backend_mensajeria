<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    use HasFactory;
    public function pago(){
        return $this->hasOne(PedidosPago::class, 'pedido_id');
    }
}
