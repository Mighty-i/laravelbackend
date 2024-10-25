<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class puser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'User_ID';

    protected $fillable = [
        'username',
        'password',
        'email',
        'name',
        'phone_number',
        'image',
        'Role_ID'
    ];

    protected $hidden = [
        'password',
        // 'remember_token',
    ];
    
    public function roles()
    {
        return $this->belongsTo(roles::class, 'Role_ID', 'Role_ID');
    }

    public function quotations()
    {
        return $this->hasMany(quotations::class, 'User_ID', 'User_ID');
    }

    public function repair_status()
    {
        return $this->hasMany(repair_status::class, 'User_ID', 'User_ID');
    }

}
