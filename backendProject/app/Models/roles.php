<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    use HasFactory;

    protected $fillable = [
        'Role_name',
    ];

    public function puser()
    {
        return $this->hasMany(puser::class, 'Role_ID', 'Role_ID');
    }
}
