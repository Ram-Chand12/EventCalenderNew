<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $table = 'syncer_logs';

    protected $fillable = ['id', 'wrd_id', 'club_id', 'ref_id','entity_type','message_type','message','created_at'];

    public function golfClub()
    {
        return $this->belongsTo(golf_club::class, 'club_id');
    }

}
