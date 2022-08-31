<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class LogActivity02 extends Model
{
    use HasFactory,UUID;

    protected $fillable = [
        'event',
        'user_id',
        'user_email',
        'description'
    ];
}
