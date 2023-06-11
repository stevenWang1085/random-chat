<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFriend extends Model
{
    use HasFactory;
    use SoftDeletes, SoftCascadeTrait;

    protected $table = 'user_friends';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'status',
        'room_id'
    ];
}
