<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class repair_status extends Model
{
    use HasFactory;

    protected $fillable = [
        'User_ID',
        'Process_ID',
        'StatusType',
        'Image1',
        'Image2',
        'Image3',
        'Status',
    ];

    public function repair_process()
    {
        return $this->belongsTo(repair_process::class, 'Process_ID', 'Process_ID');
    }

    public function puser()
    {
        return $this->belongsTo(puser::class, 'User_ID', 'User_ID');
    }
}
