<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class repair_process extends Model
{
    use HasFactory;
    protected $primaryKey = 'Process_ID';

    protected $fillable = [
        'Quotation_ID',
        'Step_ID',
        'licenseplate',
        'Description',
        'Status'
    ];

    public function quotations()
    {
        return $this->belongsTo(quotations::class, 'Quotation_ID', 'Quotation_ID');
    }
    

    public function part_usage()
    {
        return $this->hasMany(part_usage::class, 'Process_ID', 'Process_ID');
    }

    public function repair_steps()
    {
        return $this->belongsTo(repair_steps::class, 'Step_ID', 'Step_ID');
    }

    public function puser()
    {
        return $this->belongsTo(puser::class);
    }

    public function repair_status()
    {
        return $this->hasMany(repair_status::class, 'Process_ID', 'Process_ID');
    }
}
