<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration', 'model', 'customerName', 'phoneNumber', 'carCode', 'year', 'insuranceCompany', 'repairDate', 'damageDetails',
    ];

    // Relationship with RepairHistory (if exists)
    public function repairHistory()
    {
        return $this->hasMany(RepairHistory::class);
    }
}
