<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'name',
        'quantity',
        'description',
        'calories',
        'carbohydrate',
        'protein',
        'fat',
        'sodium',
        'volume',
        'drink',
        'photo_name'
    ];
}
