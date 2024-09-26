<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customers extends Model
{
    use HasFactory;

    protected $primaryKey = 'Customer_ID';

    protected $fillable = [
        'google_id',
        'FirstName',
        'Lastname',
        'username',
        'password',
        'email',
        'profile_image',
        'PhoneNumber',
    ];

    public function quotations()
    {
        return $this->hasMany(quotations::class, 'Customer_ID', 'Customer_ID');
    }
}
