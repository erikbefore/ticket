<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = ['name', 'shortening', 'department_id'];
	
	public $timestamps = false;

	/*
	 * Return ancestor of current Department
	 *9
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
	*/
	public function ancestor()
	{
		return $this->belongsTo('App\Model\Department', 'department_id');
	}
	
	/*
	 * Return all descendants of current Department
	 *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function descendants()
	{
		return $this->hasMany('App\Model\Department', 'department_id', 'id');
	}
	
    /**
     * Get Members that belong to $this Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany('\PanicHDMember')->orderBy('name');
    }
	
	/**
	 * Point if this is a main department
	 *
	 * @return bool
	*/
	public function is_main()
	{
		return is_null($this->department_id) ? true : false;
	}
	
	/*
	 * Get formatted name
	*/
	public function getName()
	{
		return ucwords(mb_strtolower($this->name));
	}
	
	/*
	 * Get department name with format "Ancestor: Department"
	 * 
	 * @Return string
	*/
	public function getFullName()
	{
		$ancestor = ($this->is_main() ? '' : $this->ancestor()->first()->name . trans('panichd::lang.colon'));
		
		return ucwords(mb_strtolower($ancestor . $this->name));
	}
	
	/*
	 * Get Shortened department name with format "A: Department"
	 * 
	 * @Return string
	*/
	public function getShortName()
	{
		$shortening = $this->is_main() ? '' : $this->ancestor()->first()->shortening . trans('panichd::lang.colon');
		
		return $shortening . ucwords(mb_strtolower($this->name));
	}
}