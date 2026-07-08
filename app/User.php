<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    //    protected $guarded=[];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'status', "university_id"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }
    public function  installApp()
    {
        return $this->hasMany("App\InstallApp");
    }

    public function scopeActive($query)
    {
        return $query->where("status", 1);
    }
    public function scopePending($query)
    {
        return $query->where("status", 2);
    }
    public function scopeNew($query)
    {
        return $query->where("status", 0);
    }

    public function approver()
    {
        return $this->belongsTo(\App\User::class, "approved_by");
    }
    public function university()
    {
        return $this->belongsTo(\App\University::class);
    }
}