<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	/**
    * Get the comments for the post.
    */
    public function comments()
    {
        return $this->hasMany('App\Model\Comment');
    }
}
