<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Video extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function asset(){
        
        return $this->belongsTo(Asset::class);
    }

    
    public function channel(){
        
        return $this->belongsTo(Channel::class);
    }

    public function votes(){

        return $this->morphMany(Vote::class, 'voteable');
    }

    public function comments(){

        //if it has a comment_id then it means it's a reply on a comment and not an actual comment
        
        return $this->hasMany(Comment::class)->whereNull('comment_id')->orderBy('created_at', 'DESC');
        
    }
}
