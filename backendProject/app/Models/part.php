<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class part extends Model
{
    use HasFactory;

    protected $primaryKey = 'Part_ID';

    protected $fillable = [
        'Category_ID',
        'Vehicle_ID',
        'Name',
        'Description',
        'Quantity',
        'PricePerUnit'
    ];

    public function category_part()
    {
        return $this->belongsTo(category_part::class, 'Category_ID', 'Category_ID');
    }

    public function vehicles()
    {
        return $this->belongsTo(vehicles::class, 'Vehicle_ID', 'Vehicle_ID');
    }

    public function part_usage()
    {
        return $this->hasMany(part_usage::class, 'Part_ID', 'Part_ID');
    }
}
