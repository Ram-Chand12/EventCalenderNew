<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class event_clubs extends Model
{
    use HasFactory;
    protected $table = 'event_clubs';
    protected $fillable = ['event_id', 'golf_club_id',];

    public function club()
    {
        return $this->belongsTo(golf_club::class, 'golf_club_id');
    }
}
