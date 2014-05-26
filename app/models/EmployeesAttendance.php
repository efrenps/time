<?php

class EmployeesAttendance extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'UserId' => 'required',
		'TimeIn' => 'required',
		'TimeOut' => 'required',
		'ReasonLeave' => 'required',
		'HoursWorked' => 'required'
	);
}
