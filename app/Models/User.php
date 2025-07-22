<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable , HasRoles , HasApiTokens;


    protected $fillable = [
        'first_name',
        'second_name',
        'third_name',
        'email',
        'phone',
        'password',
        'status',
        'is_verified'
    ];


    public function conversationsStarted()
    {
        return $this->hasMany(Conversation::class, 'sender_id');
    }

    public function conversationsReceived(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Conversation::class, 'receiver_id');
    }

    public function conversations()
    {
        return Conversation::where('sender_id', $this->id)
            ->orWhere('receiver_id', $this->id);
    }

    public function userMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function orderCustomers()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function orderEmployees()
    {
        return $this->hasMany(Order::class, 'employee_id');
    }

    public function orderShippingManagers()
    {
        return $this->hasMany(Order::class, 'shipping_manager_id');
    }

    public function orderAccountant()
    {
        return $this->hasMany(Order::class, 'accountant_id');
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'customer_id');
    }

    public function manyUserOffer()
    {
        return $this->hasMany(UserOffer::class);
    }

    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
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
