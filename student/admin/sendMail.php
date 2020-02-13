<?php 

include('header.php');
$imessage="";
$date2="";
$date1="";
$class="";
if (isset($_POST['analyze'])) {
 
			$connect = new PDO("mysql:host=localhost;dbname=attendance","root","");
			$date1=$_POST['date1'];
			$date2=$_POST['date2'];
      $class=$_POST['grade_id'];
			$output="";
			$currentDateTime = date('Y-m-d');
    		if ($date2>$currentDateTime) {
    			$imessage="You cannot Select a future date as a reference date.";
    		}
        /*
    		else{
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
					$imessage ="No attendance records for the specified period";
				}*/
				else{

echo "
<div class='container' style='margin-top:30px'>
  <div class='card'>
  	<div class='card-header'>
      <div class='row'>
        <div class='col-md-9'>Student List</div>
        <div class='col-md-3' align='right'>
        </div>
      </div>
    </div>
  	<div class='card-body'>
  		<div class='table-responsive'>
        <table class='table table-striped table-bordered' >
          <thead>
            <tr>
              <th>Student Name</th>
              <th>Admission Number</th>
              <th>No of missed classed</th>
              <th>Parent Number</th>
              <th>Sms Status</th>
            </tr>";
				//checking students that belong to the class
				$query = "
				SELECT * FROM tbl_student WHERE student_grade_id =$class
					";

				$statement = $connect->prepare($query);
				$statement->execute();
				$result = $statement->fetchAll();
				foreach($result as $row)
				{

				//picking absent dates per student
				$sub_query = "
				SELECT * FROM tbl_attendance 
				WHERE student_id = '".$row['student_id']."' AND attendance_status = 'absent'
				AND (attendance_date BETWEEN '".$date1."' AND '".$date2."') 
				ORDER BY attendance_date ASC
				";
				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
				$count=count($sub_result);
				if ($count>=3 && $count<6){
			
					echo "<tr>
					<td>".$row['student_name']."</td>
					<td>".$row['student_roll_number']."</td>
					<td>".$count."</td>
					<td>".$row['parent_number']."</td>
					<td><label class='badge badge-success'>Sent Successfully to parent and deputy principal</label></td>
					<tr>";

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
      $results = $gateway->sendMessage('0706783609', $message);         
      foreach($results as $result) {
        // status is either "Success" or "error message" 
       
      }
    }
    catch ( AfricasTalkingGatewayException $e )
    {
      echo "Encountered an error while sending: ".$e->getMessage();
    }


			}
		
			elseif ($count>=6){
			
					echo "<tr>
					<td>".$row['student_name']."</td>
					<td>".$row['student_roll_number']."</td>
					<td>".$count."</td>
					<td>".$row['parent_number']."</td>
					<td><label class='badge badge-success'>Sent Successfully to Parent, Deputy principal and Principal</label></td>
					<tr>";
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
      $results = $gateway->sendMessage("0706783609", $message);         
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
echo $output;



echo "
          </thead>
          <tbody>

          </tbody>
        </table>
  		</div>
  	</div>
  </div>
</div>
";

}
 ?>


<!DOCTYPE html>
<html>
<head>
	<title>Mail the parents</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>


 <div class="row">
	<div class="col-md-4"></div>
 <div class="col-md-4" style="margin-top:20px;">
      <div class="card">
        <div class="card-header">Select the Range</div>
        <div class="card-body">
        	<p class="alert alert-danger"> <?php echo $imessage; ?> </span>
          <form method="post" action="">
           <div class="form-group">
          <select name="grade_id" id="grade_id" class="form-control" required>
            <option value="">Select Class</option>
            <?php
            echo load_grade_list($connect);
            ?>
          </select>
          <span id="error_grade_id" class="text-danger"></span>
        </div>
           <div class="form-group">
              <label>From: </label>
              <input type="date" name="date1" required placeholder="Select starting date" class="form-control">
            </div>
            <div class="form-group">
              <label>To:</label>
              <input type="date" name="date2" required placeholder="Select final date" class="form-control">  
            </div>
            <div class="form-group">
              <input type="submit" name="analyze"  class="btn btn-info btn-block" value="Analyze attendance" />
              <p><span><small>The system will send sms to parents if the child has missed numerous classes</small></span></p>
            </div>
          </form>
        </div>
      </div>
    </div>

</div>
</body>
</html>

