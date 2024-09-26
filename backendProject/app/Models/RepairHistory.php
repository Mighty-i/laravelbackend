<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id', 'date', 'details',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
