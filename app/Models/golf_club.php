<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class golf_club extends Model
{
    use HasFactory;
    protected $table = 'golf_club'; 

    protected $fillable = ['username','password','salt', 'url', 'group_name', 'status', 'club_name']; 

     public function logs()
    {
        return $this->hasMany(Log::class, 'club_id');
    }
    public function golfGroup()
    {
        return $this->belongsTo(golf_group::class);
    }
}
