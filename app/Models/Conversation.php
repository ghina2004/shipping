<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable =[
        'sender_id','receiver_id' , 'order_id'
    ];
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function conversationMessages()
    {
        return $this->hasMany(Message::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
