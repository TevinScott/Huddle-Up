<!DOCTYPE html>

<!--
my-huddles.php - Displays a list containing all of $user's Huddles

author Vance Field
version 29-Apr-2018
-->

<?php


  session_start();

   if (!isset($_SESSION['userlogin'])) {

       header('Location: ../Homepage/homepage.php');
   } else {
		//$user = $_SESSION['userlogin'];
   }



 ?>
<html>

<head>
    <style>
        <?php include 'my-huddles.css'; ?>
    </style>
</head>

<body>

<?php
include '../css/bootstrap.php';
include '../common/header.php';
       require_once("../db-utils.php");

displayHeader(true);
    
    
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                   
                   $huddleid = $_POST['huddleid'];
                   $choice = $_POST['whichform'];
                   if($choice == "delete"){
                     $return = deleteHuddle($huddleid);
                   //echo $return;  
                   }
                   
                   else
                   {
                       
                       session_start();
                       $user = $_SESSION['userlogin'];
                       $return = leaveHuddle($huddleid, $user);
                       //echo $return;
                   }
                   
               }
?>


<?php
    
     
/**
 * Displays the Huddles that $user is/has hosted
 */


function displayHostedHuddles($type){
    // establish connection to the database
    $conn = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");
    $user = $_SESSION['userlogin'];

    // select all rows of memberships table that $user is in
    $query = "SELECT * FROM memberships WHERE huddlee='$user'";
    $result = $conn->query($query);

    if (mysqli_num_rows($result) > 0){

        // get all huddles $user is in
        while($row = mysqli_fetch_assoc($result)){
            // get huddle_id
            $id = $row['huddle_id'];

            $conn2 = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");
            if(!$conn2){
                echo "something went wrong<br/>";
            }
            else {
                $query2 = "SELECT * FROM huddles WHERE huddle_id='$id'";
                $result2 = $conn2->query($query2);
                // should return one huddle (row)
                if (mysqli_num_rows($result2) > 0){
                    while($row2 = mysqli_fetch_assoc($result2)) {
                        // display events i am hosting
                        if ($row2['host'] != $_SESSION['userlogin']) {
                            // print joined huddle
                        } else {
                           // echo "<script>console.log('inside else');</script>";
                            // print hosted huddle
                            echo huddleToHTML($row2['img'], $row2['name'], $row2['host'], $row2['date_of'], $type, $id);
                        }
                    }
                }
            }
            mysqli_close($conn2);
        }
    }
    mysqli_close($conn);
}


function displayJoinedHuddles(){
    // establish connection to the database
    $conn = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");
    $user = $_SESSION['userlogin'];

    // select all rows of memberships table that $user is in
    $query = "SELECT * FROM memberships WHERE huddlee='$user'";
    $result = $conn->query($query);

    if (mysqli_num_rows($result) > 0){

        // get all huddles $user is in
        while($row = mysqli_fetch_assoc($result)){
            // get huddle_id
            $id = $row['huddle_id'];

            $conn2 = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");
            if(!$conn2){
                echo "something went wrong<br/>";
            }
            else {
                $query2 = "SELECT * FROM huddles WHERE huddle_id='$id'";
                $result2 = $conn2->query($query2);
                // should return one huddle (row)
                if (mysqli_num_rows($result2) > 0){
                    while($row2 = mysqli_fetch_assoc($result2)) {
                        // display events i am hosting
                        if ($row2['host'] != $_SESSION['userlogin']) {
                            // print joined huddle
                            echo huddleToHTML($row2['img'], $row2['name'], $row2['host'], $row2['date_of'], $type, $id);
                        } else {
                            //echo "<script>console.log('inside else');</script>";

                        }
                    }
                }
            }
            mysqli_close($conn2);
        }
    }
    mysqli_close($conn);
}

function huddleToHTML($img, $name, $host, $date_of, $type, $id){
    $date_of = date('F j, Y g:i A', strtotime($date_of));
    echo "
    <div class='huddle'>
        <div class='huddle-img'>
            <img class='actual-img' src='../ImageAssets/Uploads/".$img."'>
        </div>
        <div class='huddle-body'>
            <div class='huddle-name'>
                $name
            </div>
            <div class='huddle-host'>
                at $date_of,
				hosted by $host                
            </div>
        </div>";
        
       if($type == "host"){
        echo"
        <form action='huddle-details.php' method='post'>
            <input name='id' type='hidden' value='$id'>
            
            <input type='submit' class='btn btn-primary' value='View'></input>
        </form> <br>
        
            <form action'#' method='post'>
            <input name='huddleid' type='hidden' value='$id'>
            <input name='whichform' type='hidden' value='delete'>

            <input type='submit' class='btn btn-danger' value='Delete'></input>
        </form>
        </div>";
            }
    else{
        
         echo"<form action='huddle-details.php' method='post'>
            <input name='id' type='hidden' value='$id'>
            
            <input type='submit' class='btn btn-primary' value='View'></input>
        </form> <br> <form action'#' method='post'>
            <input name='huddleid' type='hidden' value='$id'>
            <input name='whichform' type='hidden' value='leave'>
            <input style='width: 20%' type='submit' class='btn btn-danger' value='Leave'></input>
            </form>
        </div>";}

        }


?>
<br/>

<div class="container">

    <!-- top row -->
    <div class="row">

        <!-- middle column -->
        <div class="col-lg-6 col-lg-offset-3">
			<center>
				<h1><strong><?php echo $_SESSION['userlogin'];?>&#39;s Huddles</strong></h1>
				<hr>
				<br/>
			</center>
            <div class="my-hosted-huddles">
                <center>
					<h1><strong>Hosted Huddles</strong></h1>
					<p><i>view your hosted huddles</i></p>
				</center>
                <?php echo displayHostedHuddles("host");?>
            </div>

        </div>

    </div>

    <br/>
    <hr>
    <br/>

    <!-- bottom row -->
    <div class="row">

        <!-- middle column -->
        <div class="col-lg-6 col-lg-offset-3">

            <div class="my-joined-huddles">
                <center>
					<h1><strong>Joined Huddles</strong></h1>
					<p><i>view your joined huddles</i></p>
				</center>
                <?php echo displayJoinedHuddles("joined");?>
            </div>

        </div>

    </div>

</div>


</body>

</html>


