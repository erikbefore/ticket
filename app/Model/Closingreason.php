<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Closingreason extends Model
{
    protected $table = 'closingreasons';

    /**
     * Get related category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function category()
    {
        return $this->belongsTo('App\Model\Category', 'category_id');
    }
	
	/**
     * Get related status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function status()
    {
        return $this->belongsTo('App\Model\Status', 'status_id');
    }

}
