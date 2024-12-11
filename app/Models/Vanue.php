<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vanue extends Model
{
    use HasFactory;
    protected $table = 'vanue'; 

    protected $fillable = ['name','vanue_description','address','city','country','state', 'postal_code','phone','website', 'status']; 
}
