<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentStatus extends Model
{
    use HasFactory;

    protected $table = 'investment_status';

    protected $fillable = [
        'investment_id',
        'name'
    ];
}
