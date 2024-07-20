<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $connection = 'mysql';
    protected $table = 'payments';
    protected $fillable = [
        'order_id', 'transaction_id', 'payment_type', 'gross_amount', 
        'transaction_time', 'transaction_status'
    ];
    public function order() {
        return $this->belongsTo(Order::class);
    }
}