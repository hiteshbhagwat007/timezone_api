<?php

// echo "here";
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

require_once('simple_html_dom.php'); // Thanks to Jose Solorzano (https://sourceforge.net/projects/php-html/) > Simple Dom parsing library
$previous = " "; // Global previous varibale to store previous value of Dom tree

function get_time_zone($hour, $minute) // Main function to dom parse
{

    $website      = "https://www.timeanddate.com/worldclock/full.html?sort=2"; //Website to retreive data of all cities
    $timezonelink = "https://www.timeanddate.com/time/zone/"; //Website to retreive data from city

    $html = new simple_html_dom();
    $timezonehtml = new simple_html_dom();
    $html->load_file($website); // load the html to int $html dom tree
    $element = $html->find("td"); // td element contains all the cities with their time right at when you called the website

    foreach ($element as $key => $sub_tree)
    {
        if (array_key_exists("0", $sub_tree->children))
        {
            $previous = $sub_tree->children[0]->attr['href'];
            // echo $previous;
            //  $sub_tree->children[0]->attr['href'] is the link of a city with its time in the next iteration ( else statment )
        }

        else
		{
            $time = trim($sub_tree); // trim the time
            $time = substr($sub_tree, -10); // this line chooses the end 10 characters which contain time
            $time = substr($time, 0, 5); //and then choose the first 5 to get time in format HH.MM ex 14.20 (24 Hours)

            $timenow      = explode(".", $time); // Seperating minute and hours
            $hour_place   = $time[0] . $time[1]; // find the hour of current city
            $minute_place = $time[3] . $time[4]; // find the minute of current city


            if ($minute_place == "am" || $minute_place == "pm" || $minute_place == "PM" || $minute_place == "AM")
            {
                $time       = substr($sub_tree, -13);
                $time       = substr($time, 0, 5);
                $timenow    = explode(":", $time);
                $hour_place = $time[0] . $time[1]; // find the hour of current city

                if ($hour_place != '12'  && ($minute_place == "pm" || $minute_place == "PM"))
                {
                    $hour_place = $hour_place + 12; // Add 12 hours if the time is past 1 pm
                }

                if ($hour_place=="12" && ($minute_place=="am" || $minute_place=="AM"))
                {
                    ;  // Do nothing 
                }
                else
				{
                    $minute_place = $time[3] . $time[4];
				}
            }
               
			echo $hour_place." $minute_place<br>";
            
			if ($hour_place == $hour && ( $minute_place!='am' && $minute_place!='AM' )) 
				// If the user entered hour matches with the current city hour go ahead to match minutes
            {
                $minute_difference =$minute_place -$minute; // find the minute difference
                // echo $minute_difference."<br>";
				
                if ($minute_difference > -15 && $minute_difference < 15) 
					// compensate for server call less
                    //matches a city's exact time in range of +-15 Minutes
                {

                    $link         = explode("/", $previous); // if matched find the city link
                    $country      = $link[2]; // country  of matched city
                    $city         = $link[3]; // the city itself
                    $timezonelink = "$timezonelink" . "$country" . "/$city"; // make the webiste page to go on for DOM Parse
                    // echo $timezonelink;


                    $timezonehtml->load_file($timezonelink);
                    $division = $timezonehtml->find('p'); // P tag contains the important data
                    $sub_dom_tree = new simple_html_dom();
                    $sub_dom_tree = $division;

                    foreach ($sub_dom_tree as $text) // Parse through the data (Max 4-5 lines)
                    {
                      if (strstr($text, "UTC/GMT")) // if the data contains this yipee we found the timezone
                        {
                            $final_time_zone = $text;
                            return $final_time_zone;
						}
					}
                 break 1;
				}
                 else
                    continue;
            }
		}
    }
	 $html->clear(); // avoid memory leak
     unset($html);
}

?>