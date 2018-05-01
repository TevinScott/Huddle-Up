<!DOCTYPE html>

<!--
huddle-details.php - Page for displaying details about an event ("Huddle")

author Vance Field
version 27-Apr-2018
-->
<?php  
  session_start();

   if (!isset($_SESSION['userlogin'])) {

        header('Location: ../Homepage/homepage.php');
   } else {
		$host = $_SESSION['userlogin'];
   }


 ?>
<html>

<head>
    <style>
        <?php include 'huddle-details.css'; ?>
    </style>

</head>

<body>

<?php
include '../css/bootstrap.php';
include '../common/header.php';
require_once("../db-utils.php");

displayHeader(true);
    

       


?>
<br/>
<br/>
<br/>
    <br>

<div class="container">
<center>

<?php 
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                   
				   $passcode = $_POST['pass'];
                   $huddleid = $_POST['huddleid'];
                   $choice = $_POST['whichform'];
                   if($choice == "delete"){
                     $return = deleteHuddle($huddleid);
                   }
                   
                   if($choice == "leave")
                   {
                       
                       session_start();
                       $user = $_SESSION['userlogin'];
                       $return = leaveHuddle($huddleid, $user);
                       if($return){
                           echo "Successfully left the Huddle";
                       }
                       else{
                           
                           echo "Failed to leave the Huddle";
                       }
                   }
                    if($choice == "join")
                    {
						// validate pw

						if(hasPasscode($huddleid)){
							$pw = getPasscode($huddleid);
							if($pw === $passcode){
								$user = $_SESSION['userlogin'];
								$return = joinHuddle($huddleid, $user);
								if($return){
									echo "Successfully joined the Huddle";
								}
								else{
									echo "Failed to join the Huddle";
								}
							}
							else {
								echo "invalid pw";
							}
						}
						else{
							$user = $_SESSION['userlogin'];
							$return = joinHuddle($huddleid, $user);
							if($return){
								echo "Successfully joined the Huddle";
							}
							else{
								echo "Failed to join the Huddle";
							}
						}
                    }
                   
               }

	$id = $_POST["id"];
	$mysqli = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");

  
	$query = $mysqli->query("SELECT * FROM huddles WHERE huddle_id = '$id'"); 
	$results = $query->fetch_assoc();
	if (mysqli_num_rows($query) > 0 ){	
		$date_created = date('F j, Y g:i A', strtotime($results['date_created']));
		$date_of = date('F j, Y g:i A', strtotime($results['date_of']));
		echo "<img style='width: 50rem' src='../ImageAssets/Uploads/". $results["img"] ."' <br>";
		echo "<h2>". $results["name"] ."</h2>";
		echo "<h4> Created by: ". $results["host"] ."</h4>";
		echo "<h4> Description: ". $results["description"] ."</h4>";
		echo "<h4> Date created: ". $date_created ."</h4>";
		echo "<h4> Dated of: ". $date_of ."</h4>";

        $hostOfHuddle = $results["host"];

  
            $query = isUserInHuddle($host, $id);
            if($host == $hostOfHuddle){
        echo" <form action'#' method='post'>
            <input name='huddleid' type='hidden' value='$id'>
            <input name='whichform' type='hidden' value='delete'>
            <input type='submit' class='btn btn-danger' value='Delete'></input>
            </form>
            ";
            }
      
			else {
				
				if($query){
					 echo" <form action'#' method='post'>
				<input name='huddleid' type='hidden' value='$id'>
				<input name='whichform' type='hidden' value='leave'>
				<input type='submit' class='btn btn-warning' value='Leave'></input>
				</form>
				";
				  
					
				}
				else{
					$isPass = hasPasscode($id);
					if ($isPass ){
						echo "<br/><form action'#' method='post'>
					   This Huddle is password protected.<br/>
				<input name='pass' type='password' placeholder='Enter passcode'></input>
				<input name='huddleid' type='hidden' value='$id'>
				<input name='whichform' type='hidden' value='join'>            
				<input type='submit' class='btn btn-primary' value='Join!'></input>
				</form>";
					}
					
					else {
						echo "<br/><form action'#' method='post'>					
				<input name='huddleid' type='hidden' value='$id'>
				<input name='whichform' type='hidden' value='join'>            
				<input type='submit' class='btn btn-primary' value='Join!'></input>
				</form>";
					}
					   
				   
				}
				
			}
				
            $huddleid = $results['huddle_id'];
            echo "<br><br>";
            echo "Number of members: " .numOfHuddleesPerHuddle($huddleid);
            
            $users = arrayOfHuddlees($huddleid);
            echo "<center><table style='text-align: center; width: 30%' class='table'><th style='text-align: center'>Name</th><thread>";

            foreach($users as $user){
                
                echo "<tr scope='row'><td>" . $user[0] . "</td></tr>";
                
            }
        
            echo "</thread></table></center>";

            
       
		
	}
	else {
	}
	
	?>
	
	<br>
	<br>







</center>
</div>
    
    
</body>
</html>