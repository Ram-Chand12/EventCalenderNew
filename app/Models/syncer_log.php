<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class syncer_log extends Model
{
    use HasFactory;
    protected $table = 'syncer_logs'; 
    protected $fillable = [
        'wrd_id',
        'club_id',
        'ref_id',
        'entity_type',
        'message_type',
        'message',       
    ];
    public $timestamps = true;
}
