<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class car_brands extends Model
{
    use HasFactory;

    protected $primaryKey = 'Brand_ID';

    protected $fillable = [
        'Brand',
    ];

    public function vehicles()
    {
        return $this->hasMany(vehicles::class, 'Brand_ID', 'Brand_ID');
    }
}
