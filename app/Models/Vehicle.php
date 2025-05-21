<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'brand',
        'model',
        'year',
        'color',
        'owner_id',
        'image',
    ];

    public function owner()
    {
        return $this->belongsTo(Customer::class, 'owner_id');
    }
}
