<!DOCTYPE html>

<!--
create-huddle.php - Page for creating a new event ("Huddle")

author Vance Field
version 27-Apr-2018
-->
<?php
require_once("../create-Huddle-postValidation.php");
require_once("../db-utils.php");
  session_start();

   if (!isset($_SESSION['userlogin'])) {

        header('Location: ../Homepage/homepage.php');
   } else {
		//$host = $_SESSION['userlogin'];
   }
 ?>
<html>

<head>
    <style>
        <?php include 'create-huddle.css'; ?>
    </style>

</head>

<body>

<?php
include '../css/bootstrap.php';
include '../common/header.php';
displayHeader(true);
?>



<?php
include_once("../create-Huddle-postValidation.php");
// if the create Huddle button is pressed
if (isset($_POST['create'])){

    // path to store the uploaded file
    $target = "../ImageAssets/Uploads/".basename($_FILES['image']['name']);

    // connect to the db
    $conn = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");

    // get data from form
    $host = NULL;
    $name = NULL;
    $desc = NULL;
    $date_of = NULL;
    $img = NULL;
    $pass = NULL;


    /* check for logged in user */
    if (!isset($_SESSION['userlogin']))
    {
        header('Location: create-huddle.php');
    }
    else
    {
        $host = $_SESSION['userlogin'];
        //echo $host, "<br/>";
    }

	// name is required
    if (!isset($_POST['name']))
    {
        // redirect
        header('Location: create-huddle.php');
    }
    else
    {
        //sanitized here
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        //echo $name, "<br/>";
    }

	// description is required
    if (!isset($_POST['description']))
    {
        header('Location: create-huddle.php');
    }
    else
    {
        $desc = mysqli_real_escape_string($conn, $_POST['description']);
        //echo $desc, "<br/>";
    }

	// capacity is required
    if (!isset($_POST['capacity']))
    {
        header('Location: create-huddle.php');
    }
    else
    {
        $capacity = mysqli_real_escape_string($conn, $_POST['capacity']);
        //echo $capacity, "<br/>";
    }

	// image is not required
    if (!isset($_FILES['image']['name']))
    {
        //$img = "NULL";
        //echo $img, "<br/>";
    }
    else
    {
        //$img = mysqli_real_escape_string($conn, $_POST['img']);
        $img = $_FILES['image']['name'];
        //echo $img, "<br/>";

        // move the uploaded image to the folder: ../ImageAssets/Uploads
        if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
            //echo "Image uploaded successful.<br/>";
        }
        else {
            //echo '<script language="javascript">';
            //echo 'alert("Image upload failed.")';
            //echo '</script>';
        }
    }

// date is required
    if (!isset($_POST['date']))
    {
        header('Location: create-huddle.php');
    }
    else
    {
        $date_of = mysqli_real_escape_string($conn, $_POST['date']);
        //echo "date_of:";
        //echo $date_of, "<br/>";
    }

	// passcode is not required
    if (!isset($_POST['passcode']))
    {
        //$pass = "NULL";
        //echo $pass, "<br/>";
    }
    else
    {
        $pass = mysqli_real_escape_string($conn, $_POST['passcode']);
        //echo $pass, "<br/>";
    }

	// build query
    $query = "INSERT INTO huddles (`host`, `name`, `description`, `passcode`, `img`, `date_of`, `capacity`)
                       VALUES ('$host', '$name', '$desc', NULLIF('$pass','NULL'), NULLIF('$img','NULL'), '$date_of', '$capacity')";

	// execute query
    //$result = $conn->query($query);
	//if(mysqli_query($conn, $query)) {
    if(allErrorMessages($_POST) === false){
        if(mysqli_query($conn, $query)) {
            // must grab the latest huddle_id created by $host in order to add $host to `memberships` table
              $id = mysqli_insert_id($conn);
            // insert the huddle_id and $host into `memberships` table
            insertNewMembership($id,$host);
            echo '<script language="javascript">';
            echo 'alert("New Huddle creation worked!.")';
            echo '</script>';

        }
        else {
            echo '<script language="javascript">';
            echo 'alert("New Huddle creation unsuccessful.")';
            echo '</script>';
        }
    }
	// close database connection
    mysqli_close($conn);

}

/**
 * Inserts a new members of given $id and $huddlee.
 * This is currently only used to insert a new Membership when a new Huddle is created.
 * @param $id a huddle_id of a huddle
 * @param $huddlee a huddlee (user)
 */
function insertNewMembership($id, $huddlee){

            $result = joinHuddle($id, $huddlee);
            if ($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo '<script language="javascript">';
                    echo 'alert("New Huddle creation successful!")';
                    echo '</script>';
                }
            }
            else{
                echo "0 results in membershipsResult";
            }
            mysqli_close($conn);
}


?>

<br/>

<div class="container">

    <!-- top row -->
    <div class="row">

        <!-- column holding user input fields -->
        <div class="col-lg-6 col-lg-offset-3">
	
			<center><h1><strong>Create a new Huddle</strong></h1></center>
			<hr>
		
            <!-- new Huddle input form -->
            <form action="create-huddle.php" method="post" enctype="multipart/form-data">

                <!-- huddle name -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter a name for your Huddle (3-25 character limit)" required/>
                </div>

                <!-- huddle description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" rows="3" id="description" name="description" placeholder="Enter a description for your Huddle (200 character limit)" required></textarea>
                </div>

                <!-- huddle capacity -->
                <div class="form-group">
                    <label for="description">Capacity</label>
                    <select required class="form-control" id="capacity" name="capacity">
                        <option selected disabled>Please select</option>
						<option>Unlimited</option>
                        <option>5</option>
                        <option>10</option>
                        <option>20</option>
                    </select>
                </div>

                <!-- huddle date -->
                <div class="form-group">
                    <label for="date">Date of</label>
                    <input class="form-control" type="datetime-local" id="date" name="date" required>
                </div>

                <!-- huddle image -->
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file"class="custom-file-input" name="image">
                </div>

                <!-- huddle passcode -->
                <div class="form-group">
                    <label for="passcode"><input type="checkbox" class="form-check-input" id="enablePass" onclick="enableDisable(this.checked, 'passcode')"> Enable Passcode</label>
                    <input type="password" class="form-control" id="passcode" name="passcode" placeholder="Enter a numeric value of 4 to 10 digits in length (Ex: '123123')" disabled/>
                    <label for="passcode">A passcode makes your Huddle private.</label>
                </div>
                <div align="center">
                    <!-- submit button -->
                    <button type="submit" name="create" class="btn btn-success">Create</button>

                    <!-- submit button -->
                    <button type="button" class="btn btn-danger" onclick="cancelHuddle()">Cancel</button>
                </div>
                <br/>
                <script type="text/javascript">
					/**
					 * Clears all user data entered on screen
					 */
                    function cancelHuddle() {
                        document.getElementById("name").value = "";
                        document.getElementById("description").value = "";
                        document.getElementById("capacity").selectedIndex = 0;
                        document.getElementById("date").value = "";
						document.getElementById("enablePass").checked = false;
                        document.getElementById("passcode").value = "";
						document.getElementById("passcode").disabled = true;
                    }

					/**
					 * Toggles the `passcode` textBox
					 */
					function enableDisable(bEnable, textBoxID) {
                        document.getElementById(textBoxID).disabled = !bEnable
                    }

                </script>

            </form>

        </div>

    </div>


</div>
<?php
require_once("../create-Huddle-postValidation.php");
if (isset($_POST['create'])){
    echo returnErrorMessages($_POST);
}
?>



</body>

</html>
