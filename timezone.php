<?php

/*
Written by Ashu @ https://https://github.com/infinite4evr
Please Contribute to the project if you can

Any structural changes in the site ->

https://www.timeanddate.com/worldclock/full.html?sort=0
https://www.timeanddate.com/time/zone/

Will lead to the faulty working of the function and it may not send timezone to you
Since this library works on DOM Parsing of Web Pages
Although we will try to keep this library updated

Credits : 
Website https://www.timeanddate.com/ for being there to find the timezones
Jose Solorzano (https://sourceforge.net/projects/php-html/) > Simple Dom parsing library

*/

require_once 'simple_html_dom.php'; 
$previous = " ";

function get_time_zone($hour, $minute)

{

    $website = "https://www.timeanddate.com/worldclock/full.html?sort=0"; 
    $timezonelink = "https://www.timeanddate.com/time/zone/";

    $html = new simple_html_dom();
    $timezonehtml = new simple_html_dom();
    $html->load_file($website); 
    $element = $html->find("td"); 

    foreach ($element as $key => $sub_tree)
    {
        if (array_key_exists("0", $sub_tree->children)) 
        {
            $previous = $sub_tree->children[0]->attr['href'];
            // echo $previous;
            //  $sub_tree->children[0]->attr['href'] is the link of a city with its time in the next iteration 
        }
        else
        {
            $time = trim($sub_tree); 
            $time = substr($sub_tree, -10); 
            $time = substr($time, 0, 5); 

            $timenow      = explode(".", $time); 
            $hour_place   = $time[0] . $time[1]; 
            $minute_place = $time[3] . $time[4];

            if ($minute_place == "am" || $minute_place == "pm" || $minute_place == "PM" || $minute_place == "AM")
            {
                $time       = substr($sub_tree, -13);
                $time       = substr($time, 0, 5);
                $timenow    = explode(":", $time);
                $hour_place = $time[0] . $time[1]; 

                if ($hour_place != '12' && ($minute_place == "pm" || $minute_place == "PM"))
                {
                    $hour_place = $hour_place + 12; 
                }

                if ($hour_place == "12" && ($minute_place == "am" || $minute_place == "AM"))
                {
                    $hour_place = "00";
                    $minute_place = $time[3] . $time[4];
                }
                else
                {
                    $minute_place = $time[3] . $time[4];
                }
            }
            
            if ($hour_place == $hour && ($minute_place != 'am' && $minute_place != 'AM'))
            {
                $minute_difference = $minute_place - $minute; // find the minute difference
                // echo $minute_difference."<br>";

                if ($minute_difference > -10 && $minute_difference < +10)
                {

                    $link    = explode("/", $previous); 
                    $country = $link[2]; 
                    $city    = $link[3];                    
                    $timezonelink = "$timezonelink" . "$country" . "/$city"; 
                    $timezonehtml->load_file($timezonelink);
                    $division = $timezonehtml->find('p'); 
                    $sub_dom_tree = new simple_html_dom();
                    $sub_dom_tree = $division;

                    foreach ($sub_dom_tree as $text) 
                    {
                        if (strstr($text, "UTC/GMT")) 
                        {
                            $final_time_zone = $text;
                            $final_time_zone = (string) $final_time_zone;
                            $final_time_zone = substr($final_time_zone, -23);
                            $final_time_zone = substr($final_time_zone, 0, 6);

                            for ($i = 0; $i < strlen($final_time_zone); ++$i) {if ($final_time_zone[$i] == "+" || $final_time_zone[$i] == "-") 
                            {
                                return "UTC/GMT " . substr($final_time_zone, $i);
                            }

                        }

                    }
          }
         break 1;
       }
        else 
        {
                    continue;
        }

            }
        }
    }
    $html->clear(); // avoid memory leak
    unset($html);
}

?>
