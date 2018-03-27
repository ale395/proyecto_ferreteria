<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    
    public function users()
	{
    	return $this
        	->belongsToMany('App\User')
        	->withTimestamps();
	}

	public function permissions()
    {
        return $this
            ->belongsToMany('App\Permission')
            ->withTimestamps();
    }

	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'description',
    ];


}
