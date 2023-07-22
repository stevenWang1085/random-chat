<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

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

    protected $dates = [
        'created_at'
    ];

    protected $dateFormat = [];

    public function relationRoom()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date) : string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
