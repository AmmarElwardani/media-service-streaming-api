<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


class Channel extends Model implements HasMedia
{
    // use HasFactory;

    use HasMediaTrait;
    use HasFactory;

    //protected $guarded = [];

    public function user(){
        
        return $this->belongsTo(User::class);
    }

    public function image(){

        if($this->media->first()){

            return $this->media->first()->getFullUrl('thumb');
        
        }
        
        return null;
    }

    //Checks if the user is authenticated or authorized
    public function editable(){

        if( ! auth()->check()) 
            return false ;

        return $this->user_id === auth()->user()->id;

    }

    public function registerMediaConversions(?Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100);
            //->nonQueued(); //Because the Conversion work as a "queue"
    }

    /**
     * A channel has many subscriptions
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
