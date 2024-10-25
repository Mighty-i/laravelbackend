<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quotations extends Model
{
    use HasFactory;

    protected $primaryKey = 'Quotation_ID';

    protected $fillable = [
        'Customer_ID',
        'User_ID',
        'Company_ID',
        'Vehicle_ID',
        'QuotationDate',
        'TotalAmount',
        'Status',
        'color',
        'licenseplate',
        'damageassessment',
        'problemdetails',
        'RepairCost',
        'completionDate',
        'PaymentMethod',
        'PaymentDate',

    ];

    public function customers()
    {
        return $this->belongsTo(customers::class, 'Customer_ID', 'Customer_ID');
    }

    public function puser()
    {
        return $this->belongsTo(puser::class, 'User_ID', 'User_ID');
    }
    public function insurance_company()
    {
        return $this->belongsTo(insurance_company::class, 'Company_ID', 'Company_ID');
    }
    public function vehicles()
    {
        return $this->belongsTo(vehicles::class, 'Vehicle_ID', 'Vehicle_ID');
    }
    public function repairProcesses()
    {
        return $this->hasMany(repair_process::class, 'Quotation_ID', 'Quotation_ID');
    }
}
