<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class repair_steps extends Model
{
    use HasFactory;

    protected $fillable = [
        'StepName',
    ];

    public function repairProcesses()
    {
        return $this->hasMany(repair_process::class, 'Step_ID', 'Step_ID');
    }
}
