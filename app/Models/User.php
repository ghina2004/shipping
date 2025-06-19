<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'first_name',
        'second_name', 'email_verified_at',
        'email','phone_number', 'status',
        'password',
    ];

    public function conversationsStarted()
    {
        return $this->hasMany(Conversation::class, 'sender_id');
    }

    // المحادثات التي شارك فيها هذا المستخدم (كطرف ثاني)
    public function conversationsReceived()
    {
        return $this->hasMany(Conversation::class, 'receiver_id');
    }

    // جميع المحادثات (كمشارك بأي طرف)
    public function conversations()
    {
        return Conversation::where('sender_id', $this->id)
            ->orWhere('receiver_id', $this->id);
    }

    // الرسائل التي أرسلها هذا المستخدم
    public function userMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function cartCustomers()
    {
        return $this->hasMany(Cart::class, 'customer_id');
    }

    public function cartEmployees()
    {
        return $this->hasMany(Cart::class, 'employee_id');
    }

    public function cartShippingManagers()
    {
        return $this->hasMany(Cart::class, 'shipping_manager_id');
    }

    public function manyUserOffer()
    {
        return $this->hasMany(UserOffer::class);
    }

    public function image()
    {
        return $this->morphOne(Media::class, 'imageable');
    }

    public function reports()
    {
        return $this->hasMany(Report::class ,'report_for_id');
    }

    public function customersRating()
    {
        return $this->hasMany(Rating::class, 'customer_id');
    }
    public function employeesRating()
    {
        return $this->hasMany(Rating::class, 'employee_id');
    }

    public function userNotifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function userShipmentAnswers()
    {
        return $this->hasMany(ShipmentAnswer::class);
    }

    public function filesInvoice()
    {
        return $this->hasMany(InvoiceFile::class);
    }



    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
