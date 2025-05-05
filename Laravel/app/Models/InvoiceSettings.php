<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'tax_id',
        'address',
        'phone',
        'email',
        'prefix',
        'logo_path',
        'terms',
        'notes',
    ];
} 