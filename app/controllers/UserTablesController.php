<?php

class UserTablesController extends BaseController {

    /**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function get_dashboard()
	{
		return View::make('dashboard');
	}

    public function get_listEmployees()
    {
      $term = Input::get('term');
        $term = '%'.$term.'%';
        $data = array();

        // Stores the resultset data from the employees tables into the $search variable
        $search = DB::select( DB::raw("SELECT userstable.UserID, userstable.FirstName, UsersTable.LastName 
          FROM UsersTable WHERE FirstName + ' ' + LastName LIKE :value AND CompanyCode = 1"), array(
                     'value' => $term,
                   ));

        /* Iterate through the query result and stores the
        *  first_name concatenates with last_name columns and
        *  store the value into the data array
        */
        foreach ($search as $result => $employeeInfo) {
            $data[] = array('id' => $employeeInfo->UserID,
                'value' => $employeeInfo->FirstName . ' '. $employeeInfo->LastName,
                'description' => $employeeInfo->FirstName . ' '. $employeeInfo->LastName);
        }
     
         // Return an array in json format
        return json_encode($data);
    }

    public function get_employeeActions(){
        // stores the term GET value
        $name = Input::get('name');
        $name = $name.'%';
        $data = array();

        if ($name != null | $name != '') {
            $tsql = DB::select( DB::raw("SELECT FirstName, LastName, HoursWorked, TimeIn, TimeOut, ReasonLeave 
                                FROM UsersTable as u LEFT JOIN EmployeeAttendance ON u.UserID = EmployeeAttendance.UserId
                                WHERE FirstName + ' ' + LastName LIKE :value AND CompanyCode = 1
                                ORDER BY TimeId DESC"), array(
                                'value' => $name,
                    ));
        } else {
            $tsql = DB::table('UsersTable')
                         ->leftJoin('EmployeeAttendance', 'UsersTable.UserID', '=', 'EmployeeAttendance.UserId')
                         ->where('CompanyCode', '=', '1')
                         ->get();
        }
        
        // Convert server timezone to local timezone
        $localTimezone = Input::get('localTimezone');
        $ConvertDate = new UserTablesController();
        
        $table = "<table>
                  <tr>
                    <td></td>
                    <td>Name</td>
                    <td>Time</td>
                    <td>Action</td>
                    <td>Reason</td>
                  <tr/>";

                if ( !empty($tsql) ) {
                  foreach ($tsql as $result => $employeeInfo) {
                      $table .= "<tr>"; 
                      $table .= "<td>" . '<img src="images/employee.png" />'. "</td>"; 
                      $table .= "<td>" . $employeeInfo->FirstName . ' ' . $employeeInfo->LastName . "</td>";
                       
                      if ($employeeInfo->HoursWorked == "0"){
                        $localTimeIn = $employeeInfo->TimeIn; 
                        if (!empty($localTimeIn)) {
                            $localTimeIn = $ConvertDate->ConvertTimeZone('',$localTimeIn,$localTimezone,0); 
                        }                      
                          $table .= '<td class="Time">' . $localTimeIn . "</td>";                       
                          $table .= "<td>" . "Start Work" . "</td>"; 
                      } else {
                        $localTimeOut = $employeeInfo->TimeOut; 
                        if (!empty($localTimeOut)) {
                            $localTimeOut = $ConvertDate->ConvertTimeZone('',$localTimeOut,$localTimezone,0); 
                        }                         
                          $table .= '<td class="Time">' . $localTimeOut . "</td>";                      
                          $table .= "<td>" . "Stop Work" . "</td>";
                      }
                      $table .= "<td>" . $employeeInfo->ReasonLeave . "</td>"; 
                      $table .= "</tr>"; 
                  }
                } 

               $table .= "</table>";  

        return $table;
    }

    public function post_authenticate(){
        $id = Input::get('userid');
        $password = Input::get('password');
        //$password = sha1($password);
        $data = array();

        #search database
        $employee = DB::table('UsersTable')
                    ->where('UserID', '=', $id)
                    ->where('Password', '=', $password)
                    ->first();

        if (empty($employee)) {
                $error = 1;//empty employee
                $data[] = array('error' => $error); 

            return json_encode($data);
        }

        $employeeAttendance = DB::table('EmployeeAttendance')
                            ->where('UserId', '=', $employee->UserID)
                            ->where('HoursWorked', '=', '0')
                            ->first();

        if (empty($employeeAttendance)) {
            // Employee start work
            $workStatus = 0;
        } else {
            // Employee stop work
            $workStatus = 1;
        }

        $error = 0;//empty employee
        $data[] = array('FirstName' => $employee->FirstName, 
                     'FullName' => $employee->FirstName . ' ' . $employee->LastName,
                     'type' => $employee->IsSupervisor,
                     'Action' => $workStatus,
                     'error' => $error); 

        return json_encode($data);

    }

	public function post_SaveStartWork()
    {
        // data employee attendance
        $employeeId = Input::get('userid');
        $action = Input::get('action');
        $reasonLeave = Input::get('reason');
        $timeIn = date_create()->format('Y-m-d H:i:s');
        $data = array();

        if ($action == 'Start') {
            #Insert Database
            $attendanceId = DB::table('EmployeeAttendance')
                ->insert(array('UserId' => $employeeId, 'TimeIn' => $timeIn, 'ReasonLeave' => $reasonLeave, 'Action' => $action));
             
        } else {
            $employeeAttendance = DB::table('EmployeeAttendance')
                                 ->where('UserId', '=', $employeeId)
                                 ->where('HoursWorked', '=', '0')
                                 ->first();

            $initial_date = date_create($employeeAttendance->TimeIn);
            $initial_date = date_format($initial_date, 'Y-m-d H:i:s');
            $end_date = $timeIn;

            // Split the initial date into pieces
            list($initial_day, $initial_hour) = explode(' ', $initial_date);
            list($year, $month, $day) = explode('-', $initial_day);
            list($hour, $minute, $second) = explode(':', $initial_hour);
            $initial_time = mktime($hour + 0, $minute + 0, $second + 0, $month + 0, $day + 0, $year);

            // Split the end date into pieces
            list($end_day, $end_hour) = explode(" ", $end_date);
            list($year, $month, $day) = explode("-", $end_day);
            list($hour, $minute, $second) = explode(":", $end_hour);
            $end_time = mktime($hour + 0, $minute + 0, $second + 0, $month + 0, $day + 0, $year); 

            // Make the difference betweeen the SECONDS in the dates
            //$seconds_difference = $end_time - $initial_time;
            $seconds_difference = time() - $initial_time;

            // Calculate total hours worked
            // Divide ($seconds_difference / 60) / 60
            $total_hours_worked = ( $seconds_difference / 60 ) / 60;

            // $reason = Input::get('reason');
            // $employeeId = Input::get('employeeId');
               $resultUpdateAttendance = DB::table('EmployeeAttendance')
                ->where('TimeId', $employeeAttendance->TimeId)
                ->update( array( 'TimeOut' => $timeIn,
                                 'HoursWorked' => $total_hours_worked,
                                 'ReasonLeave' => $reasonLeave));   
                   
        }

        $employeeData = DB::table('UsersTable')
                        ->where('UserID','=', $employeeId)
                        ->first();

        $data[] = array("FullDescription"=>$employeeData->FirstName.' ' .$employeeData->LastName,
                "Action" => $action,
                "Reason"=>$reasonLeave,
                "Time"=>$timeIn);

        return json_encode($data);
    }
    
    public function post_AttendanceData()
    {
        $employeeId = Input::get('userid');
        $data = array();

          $employeeData = DB::table('EmployeeAttendance')
                        ->where('UserId','=', $employeeId)
                        ->orderBy('TimeId','desc')
                        ->first();

          if ($employeeData->HoursWorked == 0) {
                $validateDate = 0;
          } else {
                $validateDate = 1;
          }
          
          // Convert server timezone to local timezone
          $serverDate = $employeeData->TimeIn;
          $localTimezone = Input::get('localTimezone');
          $ConvertDate = new UserTablesController();         
          $localDate = $ConvertDate->ConvertTimeZone('',$serverDate,$localTimezone,0);
          
          $data[] = array('Id' => $employeeData->TimeId, 
                     'Time' => $localDate,
                     'hora' => $employeeData->HoursWorked,
                     'ReasonLeave' => $employeeData->ReasonLeave,
                     'Action' => $validateDate); 

        return json_encode($data);         
    }

    public function post_SaveManual()
    {

        $Id = Input::get('id');
        $Action = Input::get('Action');
        $Supervisor = Input::get('Supervisor');
        $employeeName = Input::get('employeeName'); 
        $reasonLeave = 'A manual entry for ' . $employeeName . ' was added by '.$Supervisor;
        $timeIn = Input::get('Timein');
        $timeOut= Input::get('Timeout');
        $UserID = Input::get('UserID');
        $data = array();
        
        $initial_date = date_create($timeIn . ':00');
        $initial_date = date_format($initial_date, 'Y-m-d H:i:s');
        
        $end_date = $timeOut . ':00';
        
        // Split the initial date into pieces
        list($initial_day, $initial_hour) = explode(" ", $initial_date);
        list($year, $month, $day) = explode("-", $initial_day);
        list($hour, $minute, $second) = explode(":", $initial_hour);
        $initial_time = mktime($hour + 0, $minute + 0, $second + 0, $month + 0, $day + 0, $year);
         
        // Split the end date into pieces
        list($end_day, $end_hour) = explode(" ", $end_date);
        list($year, $month, $day) = explode("-", $end_day);
        list($hour, $minute, $second) = explode(":", $end_hour);
        $end_time = mktime($hour + 0, $minute + 0, $second + 0, $month + 0, $day + 0, $year);

        // Make the difference betweeen the SECONDS in the dates
        $seconds_difference = $end_time - $initial_time;
        
        // Calculate total hours worked
        // Divide ($seconds_difference / 60) / 60
        $total_hours_worked = ( $seconds_difference / 60 ) / 60;
            
        // Convert local date to server date
        $ConvertDate = new UserTablesController();   
        $localTimezone = Input::get('localTimezone'); 
        $timeIn = $ConvertDate->ConvertTimeZone($timeIn,'',$localTimezone,1); 
        $timeOut = $ConvertDate->ConvertTimeZone($timeOut,'',$localTimezone,1);
            
        if ($Action == 0) {
           $resultUpdateAttendance = DB::table('EmployeeAttendance')
                ->where('TimeId', $Id)
                ->update( array( 'TimeIn' => $timeIn,
                                 'TimeOut' => $timeOut,
                                 'HoursWorked' => $total_hours_worked,
                                 'ReasonLeave' => $reasonLeave));   
        } else {
           $attendanceId = DB::table('EmployeeAttendance')
                ->insert(array('UserId' => $UserID,
                   'TimeIn' => $timeIn,
                   'TimeOut' => $timeOut,
                   'ReasonLeave' => $reasonLeave,
                   'Action' => 'Start-Stop',
                   'HoursWorked' => $total_hours_worked));
        }
            
        return json_encode($reasonLeave);
    }
    
    // $type = define type of convertion timezone
    // type 0 = convert server timezone to local timezone
    // type 1 = convert local timezone to server timezone
    public function ConvertTimeZone($localDate, $serverDate, $localTimezone, $type)
    {
        $serverTimezone = date_default_timezone_get(); 
        
        //Convert server timezone to local timezone
        if ($type == 0) {
            $localDate = new DateTime($serverDate, new DateTimeZone($serverTimezone));
            $localDate->setTimezone(new DateTimeZone($localTimezone));
            $localDate = $localDate->format('Y-m-d H:i');

            return $localDate;
         } 
         
         if ($type == 1) {
             $serverDate = new DateTime($localDate, new DateTimeZone($localTimezone));
             $serverDate->setTimezone(new DateTimeZone($serverTimezone));
             $serverDate = $serverDate->format('Y-m-d H:i');
             return $serverDate;
         }
    }
}
