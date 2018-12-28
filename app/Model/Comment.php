<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ContentEllipse;

class Comment extends Model
{
    use ContentEllipse;

    protected $table = 'panichd_comments';
	
	/**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['ticket'];

	/**
	 *
	*/
	public function delete()
	{
		$a_errors = [];
		foreach($this->attachments()->get() as $att){
			$error = $att->delete();
			if($error) $a_errors[] = $error;
		}
		
		$error = $a_errors ? implode('. ', $a_errors) : null;
		if ($error != "") return $error;
		
		parent::delete();
	}
	
	
    /**
     * Get related ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo('App\Model\Ticket', 'ticket_id');
    }

	// TODO: Delete user() method
    /**
     * Get comment related \PanicHDMember.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\PanicHDMember', 'user_id');
    }
	
	/**
     * Get Comment owner as PanicHDMember model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('\PanicHDMember', 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'comment_id')->orderByRaw('CASE when mimetype LIKE "image/%" then 1 else 2 end');
    }
	
	/**
	 * Filter visible comments depending on member level
	*/
	public function scopeForLevel($query, $level)
	{
		// User level
		if ($level < 2) return $query->whereIN('type', ['reply', 'complete', 'completetx', 'reopen']);
		
		// For agent or admin
		return $query;
	}
	
	/**
	 * Filter comment entries that are countable as real comments (complete and reopen comments are excluded)
	*/
	public function scopeCountable($query)
	{
		return $query->whereIN('type', ['reply', 'note', 'completetx']);
	}
}
