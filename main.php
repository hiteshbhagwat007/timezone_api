<?php

	/*

	Developed by Ashu @ https://https://github.com/infinite4evr 
	Please Contribute to the project if you can 

	Any structural changes in the site -> 

	https://www.timeanddate.com/worldclock/full.html?sort=0
	https://www.timeanddate.com/time/zone/ 

	Will lead to the faulty working of the function and it may not send timezone to you 
	Since this library works on DOM Parsing of Web Pages
	Althouhg we will try to keep this library updated

	And Thanks to the site https://www.timeanddate.com/ for being there to find the timezones 

	*/

    require_once("timezone.php");
	
  
	
    function find_time_zone($user_time)  
	{    
	   // $user_time -> Must be String of this type HH:MM (24 Hour clock) Example : "21:10" or "05:10" | AM/PM will not work
	   // $user_time is sent by user to request his time zone according to UTC/GMT 
	   
		$user_time=explode(":",$user_time);
		$user_hour=$user_time[0];		//Hour time of the user 
		// echo $user_hour;
		$user_minute=$user_time[1];          //Minute time of the user 
		// echo $user_minute;
		$time_zone = get_time_zone($user_hour,$user_minute);
		return $time_zone ;                  //  return time zone of the user (String) Ex : UTC/GMT +5:30 

	}
	
?>