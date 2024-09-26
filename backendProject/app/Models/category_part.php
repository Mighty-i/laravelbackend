<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category_part extends Model
{
    use HasFactory;

    protected $primaryKey = 'Category_ID';

    protected $fillable = [
        'CategoryName',
    ];

    public function part()
    {
        return $this->hasMany(part::class, 'Category_ID', 'Category_ID');
    }
}
