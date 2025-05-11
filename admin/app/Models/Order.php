<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_number',
        'total_amount',
        'status',
        'payment_status',
        'shipping_address',
        'billing_address',
        'order_date',
        'notes'
    ];

    protected $casts = [
        'order_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes for searching and filtering
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('order_number', 'like', "%{$search}%")
              ->orWhereHas('customer', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeFilterByPaymentStatus($query, $paymentStatus)
    {
        if ($paymentStatus) {
            return $query->where('payment_status', $paymentStatus);
        }
        return $query;
    }

    public function scopeFilterByDateRange($query, $dateFrom, $dateTo)
    {
        if ($dateFrom) {
            $query->whereDate('order_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('order_date', '<=', $dateTo);
        }
        return $query;
    }
} 