<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class event extends Model
{
    use HasFactory;
    protected $table = 'events';

    protected $fillable = ['name', 'description', 'status', 'slug'];


    public function clubs()
    {
        return $this->belongsToMany(golf_club::class, 'event_clubs');
    }
}
