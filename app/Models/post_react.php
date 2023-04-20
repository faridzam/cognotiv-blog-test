<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post_react extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'post_id',
        'user_id',
        'status',
    ];
}
