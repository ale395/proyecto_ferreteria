<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model 
{
	public function roles()
    {
        return $this
            ->belongsToMany('App\Role')
            ->withTimestamps();
    }

    public function permissionRole()
    {
        return $this->hasMany('App\PermissionRole');
    }

	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description',
    ];    
}