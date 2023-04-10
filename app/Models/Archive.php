<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'md5_checksum',
        'size',
        'original_name',
        'times_downloaded',
        'user_id'
    ];
}
