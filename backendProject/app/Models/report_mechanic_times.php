<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class report_mechanic_times extends Model
{
    use HasFactory;

    protected $table = 'view_report_mechanic_times';


    public $incrementing = false;
    protected $primaryKey = null;


    public $timestamps = false;
}
