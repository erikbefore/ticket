<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    protected $fillable = ['name'];

    /**
     * Get related categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function categories()
    {
        return $this->morphedByMany('App\Model\Category', 'taggable', 'taggables')->orderBy('name');
    }

    /**
     * Get related tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tickets()
    {
        return $this->morphedByMany('App\Model\Ticket', 'taggable', 'taggables');
    }
}
