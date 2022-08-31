<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRequest extends Model
{
    use HasFactory, UUID, SoftDeletes;
    protected $fillable = [
        'reason',
        'month_name',
        'project_id',
        'category_id',
        'amount',
        'staff_id'
    ];
}


