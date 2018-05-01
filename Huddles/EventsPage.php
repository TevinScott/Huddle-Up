<!DOCTYPE html>

<html>

<style>
    <?php include 'events-styles.css'; ?>
</style>

<?php

include '../css/bootstrap.php';
include '../common/header.php';
displayHeader(true);
echo "<br/>";
displayEventsPage();
?>

</html>

<?php

/**
 * Displays all of the currently listed events
 * @param $arrayOfEvents
 */
function displayEventsPage($arrayOfEvents){

    // establish connection to the database
    $conn = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");

    // query database to select all rows of Huddles table
    $query = "SELECT * FROM huddles ORDER BY huddle_id DESC";
    $result = $conn->query($query);

    if (mysqli_num_rows($result) > 0){

        // print each row
        while($row = mysqli_fetch_assoc($result)){
            //echo "row<br/>";
            newEventPanel($row['huddle_id'], $row['host'], $row['name'], $row['description'], $row['img'], $row['date']);
        }
    }


}


/**
 * This function returns a event panel within the view that displays basic information pertaining
 * to an event
 * @param $name the name of the huddle
 * @param $description the description of the huddle
 * @param $img the image of the huddle
 * @param $date the date of the huddle
 * @return string html representation of an event as a panel
 */
function newEventPanel($id, $host, $name, $description ,$img, $date){
	require_once('../db-utils.php');
    echo "
        <div class='col-sm-6 col-md-4 col-lg-3 mt-4'>
            <div class='card'>  
				<img class='card-img-top' src='../ImageAssets/Uploads/".$img."'>
				<div class='card-block'>
					<figure class='profile'>
						<img src='http://success-at-work.com/wp-content/uploads/2015/04/free-stock-photos.gif' 
						class='profile-avatar' alt=''>
					</figure>                              
                    <h4 class='card-title mt-3'>$name</h4>
                    <div class='meta'>
                        <a>Friends</a>
                    </div>
                    <div class='card-text'>
                        $description
                    </div>
                </div>";
				if ($host===$_SESSION['userlogin'] || isUserInHuddle($_SESSION['userlogin'], $id)){
					echo "
                <div class='card-footer' style='background-color:green;'>
                    <small>$date</small>				
					<form action='huddle-details.php' method='post'>
						<input name=". '"id"'." type='hidden' value='$id'></input>
						<button type='submit' class='btn btn-secondary float-right btn-sm'>View</button>
					</form>					
                </div>";
				}
				else {
					echo "
                <div class='card-footer'>
                    <small>$date</small>				
					<form action='huddle-details.php' method='post'>
						<input name=". '"id"'." type='hidden' value='$id'></input>
						<button type='submit' class='btn btn-secondary float-right btn-sm'>View</button>
					</form>					
                </div>";
				}
				
				
				echo "
            </div>
        </div>
    ";
	
	/* next to the View button, it would be smart to display a green checkmark image if the $user is a member of the given huddle */
}

//echo $user;
?>
