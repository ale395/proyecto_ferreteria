<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Caffeinated\Shinobi\Traits\ShinobiTrait;

class Role extends Model
{
    use ShinobiTrait;

	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'roles';

    protected $fillable = [
        'id', 'name', 'description',
    ];

}
