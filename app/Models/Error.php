<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'values',
        'url',
        'status',
    ];

    protected $casts = [
        'values' => 'object',
    ];

    protected $attributes = [
        'values' => '{}',
    ];
}
