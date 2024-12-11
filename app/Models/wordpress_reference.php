<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class wordpress_reference extends Model
{
    use HasFactory;
    protected $table = 'wordpress_reference'; 

    public $timestamps = true; // Ensure timestamps are enabled
    protected $fillable = [
        'wrd_id',
        'club_id',
        'ref_id',
        'entity_type',        
        'user_name', 
        'no_of_tries', 
        'status',
        'is_varified',
     
    ];
    public static function updateWithoutTimestamp($attributes, $conditions)
    {
        return static::where($conditions)
            ->update(array_merge($attributes, ['updated_at' => DB::raw('updated_at')]));
    }
 
}
