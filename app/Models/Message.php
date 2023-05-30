<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'messages';

    protected $fillable = [
        'room_id',
        'from_user_id',
        'to_user_id',
        'message',
        'data_insert_at'
    ];
}
