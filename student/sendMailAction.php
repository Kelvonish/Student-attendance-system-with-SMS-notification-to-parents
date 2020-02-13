<?php 
include('admin/database_connection.php');
session_start();			

			$date1=$_POST['date1'];
			$date2=$_POST['date2'];
			$output="";
			$currentDateTime = date('Y-m-d');
    		#if ($date2>$currentDateTime) {
    			#echo "The month has to end for you to find the reports";
    		#else{
				//checking the month records 
				$month_query = "
				SELECT * FROM tbl_attendance 
				WHERE teacher_id = '".$_SESSION["teacher_id"]."'
				AND (attendance_date BETWEEN '".$date1."' AND '".$date2."') 
				ORDER BY attendance_date ASC
				";
				$statement = $connect->prepare($month_query);
				$statement->execute();
				$month_result = $statement->fetchAll();
				if(count($month_result)==0){
					echo "No attendance records for the specified month";
				}
				else{

				//checking students that belong to the teacher
				$query = "
				SELECT * FROM tbl_student WHERE student_grade_id = (SELECT teacher_grade_id FROM tbl_teacher 
    			WHERE teacher_id = '".$_SESSION["teacher_id"]."')
					";

				$statement = $connect->prepare($query);
				$statement->execute();
				$result = $statement->fetchAll();
				foreach($result as $row)
				{

				//picking absent dates per student
				$sub_query = "
				SELECT * FROM tbl_attendance 
				WHERE student_id = '".$row['student_id']."' AND attendance_status = 'absent' and teacher_id = '".$_SESSION["teacher_id"]."'
				AND (attendance_date BETWEEN '".$date1."' AND '".$date2."') 
				ORDER BY attendance_date ASC
				";
				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
				$count=count($sub_result);
				if ($count==3){
			
					echo $row['student_name'];
					echo "<br>";
					echo count($sub_result);
					echo "<br>";

	@$phone=$row['parent_number'];
	@$message="Your child ".$row['student_name']." has been absent from school for ".$count." days from ".$date1." to ".$date2;

 // Be sure to include the file you've just downloaded
    require_once('AfricasTalkingGateway.php');
    // Specify your login credentials
    $username   = "janetmomanyi";
    $apikey     = "e8a1f16b0bede0861de49e64d15e1ee6d178e1dac2e86768afeb3fb86345a7d1";
    // NOTE: If connecting to the sandbox, please use your sandbox login credentials
    // Specify the numbers that you want to send to in a comma-separated list
    // Please ensure you include the country code (+254 for Kenya in this case)
    $recipients = $phone;
    // And of course we want our recipients to know what we really do
    $message    = $message;
    // Create a new instance of our awesome gateway class
    $gateway    = new AfricasTalkingGateway($username, $apikey);
    // NOTE: If connecting to the sandbox, please add the sandbox flag to the constructor:
    /*************************************************************************************
                 ****SANDBOX****
    $gateway    = new AfricasTalkingGateway($username, $apiKey, "sandbox");
    **************************************************************************************/
    // Any gateway error will be captured by our custom Exception class below, 
    // so wrap the call in a try-catch block
   try 
    { 
      // Thats it, hit send and we'll take care of the rest. 
      $results = $gateway->sendMessage($recipients, $message);         
      foreach($results as $result) {
        // status is either "Success" or "error message" 
       
      }
    }
    catch ( AfricasTalkingGatewayException $e )
    {
      echo "Encountered an error while sending: ".$e->getMessage();
    }


			}
		}
}
//}
echo $output;

?>