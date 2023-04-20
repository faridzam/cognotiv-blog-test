<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post_comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'post_id',
        'user_id',
        'comment',
    ];

    public function author()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
