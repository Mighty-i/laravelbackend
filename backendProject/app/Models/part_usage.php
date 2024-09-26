<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class part_usage extends Model
{
    use HasFactory;

    protected $fillable = [
        'Process_ID',
        'Part_ID',
        'Quantity'
    ];

    public function part()
    {
        return $this->belongsTo(part::class, 'Part_ID', 'Part_ID');
    }

    public function repair_process()
    {
        return $this->belongsTo(repair_process::class, 'Process_ID', 'Process_ID');
    }
}
