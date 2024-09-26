<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class insurance_company extends Model
{
    use HasFactory;

    protected $primaryKey = 'Company_ID';

    protected $fillable = [
        'Name',
        'Address',
        'PhoneNumber'
    ];

    public function quotations()
    {
        return $this->hasMany(quotations::class, 'Company_ID', 'Company_ID');
    }
}
