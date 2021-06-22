<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;
    
    public $incrementing = false;

    
    protected static function boot(){

        parent::boot();

        static::creating(function ($model){

            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    protected $guard_name = 'api';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

        /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'registerDate' => 'date:Y-m-d',
        'deleted_at' => 'Y-m-d',
        'email_verified_at' => 'datetime',
    ];

    public function asset(){

        return $this->hasMany(Asset::class);
        
    }
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }
    
    public function isSuperAdmin(){

        return $this->hasRole('Super Admin');
    }

    public function comments(){
        
        return $this->hasMany(Comment::class);
        
    }

    public function toggleVote($entity, $type){

        $vote = $entity->votes->where('user_id', $this->id)->first();

        if ($vote) {
            $vote->update([
                'type' => $type
            ]);

            return $vote->refresh();
        
        } else  {
            
            return $entity->votes()->create([
                'type' => $type,
                'user_id' => $this->id
            ]);

        }
    }
}
