<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;
    protected $table = 'Organizers'; 

    protected $fillable = ['name', 'description', 'phone', 'website','email','status'];
}
