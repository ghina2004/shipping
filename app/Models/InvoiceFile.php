<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceFile extends Model
{
    protected $fillable =[
        'uploaded_by' , 'file_type', 'file_path' , 'user_id' ,'shipment_id'
    ];

    public function userInvoiceFile(){
        return $this->belongsTo(User::class);
    }

    public function shipmentInvoiceFile(){
        return $this->belongsTo(Shipment::class);
    }


}
