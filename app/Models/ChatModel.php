<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatModel extends Model
{
    use HasFactory;
    public function detalle_chat()
    {
        return $this->hasMany(DetalleChatModel::class, 'chat_id');
    }
    public function trabajador()
    {
        return $this->belongsTo(User::class, 'trabajador_id');
    }
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }
    public function ultimoDetalleChat()
    {
        return $this->hasOne(DetalleChatModel::class, 'chat_id')->latestOfMany();
    }
}
