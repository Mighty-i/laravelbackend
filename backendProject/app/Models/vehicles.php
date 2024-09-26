<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicles extends Model
{
    use HasFactory;

    protected $primaryKey = 'Vehicle_ID';

    protected $fillable = [
        'Brand_ID',
        'Model',
        'Year'
    ];

    public function car_brands()
    {
        return $this->belongsTo(car_brands::class, 'Brand_ID', 'Brand_ID');
    }

    public function part()
    {
        return $this->hasMany(part::class, 'Vehicle_ID', 'Vehicle_ID');
    }

    public function quotations()
    {
        return $this->hasMany(quotations::class, 'Vehicle_ID' ,'Vehicle_ID');
    }
}
