<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'calories',
        'carbohydrate',
        'protein',
        'fat',
        'sodium',
        'volume',
        'steps'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
