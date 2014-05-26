<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
		$this->call('UsertablesTableSeeder');
		$this->call('EmployeeattendancesTableSeeder');
		$this->call('EmployeesattendancesTableSeeder');
		$this->call('InfosTableSeeder');
	}

}