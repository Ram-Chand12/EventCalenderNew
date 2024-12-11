<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;
    protected $table = 'categories'; 

    protected $fillable = ['name', 'parent', 'status']; 

    public function parent()
    {
        return $this->belongsTo(category::class, 'parent');
    }

    public function children()
    {
        return $this->hasMany(category::class, 'parent');
    }
}
