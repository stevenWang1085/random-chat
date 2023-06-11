<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRoom extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'user_rooms';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'room_id',
        'user_id',
    ];

    public function relationRoom()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function relationUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
