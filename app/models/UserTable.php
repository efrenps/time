<?php

class UserTable extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'UserName' => 'required',
		'FirstName' => 'required',
		'LastName' => 'required',
		'Password' => 'required'
	);
}
