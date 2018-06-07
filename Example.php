<form action="?"  method="post">
   Time( HH:MM) :<br>
  <input type="string" name="time"><br>
  <input type="submit" value="Submit" name="submit">
</form>

<?php
// Example

require_once("main.php");

if(isset($_POST['submit']) && isset($_POST['time']))
  
  {  $time=$_POST['time'];
    // echo $time."<br>";
    echo $time_zone= find_time_zone($time) ;
  }

?>
