<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_name',
        'title',
        'subtitle',
        'image',
        'button_text',
        'button_link',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
} 