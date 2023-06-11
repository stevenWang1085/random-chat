<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes, SoftCascadeTrait;

    protected array $softCascade = ['relationUserRoom', 'relationMessage'];

    protected $table = 'rooms';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'owner_user_id',
        'type'
    ];

    public function relationUserRoom()
    {
        return $this->hasMany(UserRoom::class, 'room_id', 'id');
    }

    public function relationMessage()
    {
        return $this->hasMany(Message::class, 'room_id', 'id');
    }
}
