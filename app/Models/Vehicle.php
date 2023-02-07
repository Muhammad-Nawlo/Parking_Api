<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;


    protected $fillable = ['user_id', 'plate_number'];
    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }
}
