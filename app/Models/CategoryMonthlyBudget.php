<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\SoftDeletes;


class CategoryMonthlyBudget extends Model
{
    use HasFactory, UUID, SoftDeletes;
    protected $fillable = [
        'monthly_budget_id',
        'category_id',
        'amount',
    ];
}