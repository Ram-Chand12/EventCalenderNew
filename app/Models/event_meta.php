<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class event_meta extends Model
{
    use HasFactory;
 
    protected $table = 'event_metas' ;
    protected $fillable =['event_id','meta_key','meta_value'] ;
}
