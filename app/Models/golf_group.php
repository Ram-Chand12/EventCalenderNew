<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class golf_group extends Model
{
    use HasFactory;
    protected $table = 'golf_group'; 

    protected $fillable = ['gname','image','status']; 

    public function golfClubs()
    {
        return $this->hasMany(golf_club::class);
    }
}
