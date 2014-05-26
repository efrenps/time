<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeesAttendancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('EmployeesAttendances', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('UserId');
			$table->datetime('TimeIn');
			$table->datetime('TimeOut');
			$table->string('ReasonLeave');
			$table->float('HoursWorked');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('EmployeesAttendances');
	}

}
