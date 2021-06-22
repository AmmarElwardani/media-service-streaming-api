<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        
        return $this->belongsTo(User::class);
        
    }

    public function videos(){

        return $this->hasMany(Video::class);

    }

    public function log(){

        return $this->hasOne(Log::class);

    }

}
