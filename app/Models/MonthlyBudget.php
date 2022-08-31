<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlyBudget extends Model
{
    use HasFactory, UUID, SoftDeletes;
    protected $fillable = [
        'month',
        'month_name',
        'budget_amount',
        'ending_at',
        'carry_over',
    ];

}
