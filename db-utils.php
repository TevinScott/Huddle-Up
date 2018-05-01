<?php
/**
 * db connections and helper functions
 */

/*
 * creates Huddlee
 * returns true if successful
 */
function createHuddlee($username, $password, $permission){

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $random = '';
    $count = 11;

    for($i = 0; $i < $count; $i++){
        $random .= $characters[rand(0, $charactersLength - 1)];
    }
    $hashedrandom = hash('sha256', $random);
    $hashed = hash('sha512', hash('sha256', $password) . $hashedrandom);

    $connect = mysqli_connect("localhost", "proj6", "Huddle123!!", "proj6");

    $query = "INSERT INTO huddlees (`username`, `password`, `permission`, `salt`) VALUES ('$username', '$hashed', '$permission', '$hashedrandom')";
    $result = $connect->query($query);
    mysqli_close($connect);

    return $result;
}

/*
 * leave huddle
 * returns true is successful, otherwise returns string with error message
 */

function leaveHuddle($huddleid, $huddlee){
    $connect = mysqli_connect("localhost", "proj6", "Huddle123!!", "proj6");
    $query = "DELETE FROM memberships where huddle_id = $huddleid and huddlee = '$huddlee'";
    $result = $connect->query($query);
    mysqli_close($connect);
    return $result;
}

/*
* returns array of huddlees
*/
function arrayOfHuddlees($huddle_id){
	$connect = mysqli_connect("localhost", "proj6", "Huddle123!!", "proj6");
	$getNameOfHuddlees = mysqli_query($connect,"SELECT huddlee from memberships WHERE huddle_id = $huddle_id"); 	
	
	$array_result = array();
	while ($row = mysqli_fetch_array($getNameOfHuddlees, MYSQL_NUM)) {
		$array_result[] = $row;
	}
	mysqli_close($connect);
return $array_result;
}

/*
* returns number of huddlees in huddle
*/
function numOfHuddlees($huddle_id){
	$connect = mysqli_connect("localhost", "proj6", "Huddle123!!", "proj6");
	
	$getNumberOfHuddlees = mysqli_query($connect,"SELECT huddlee from memberships WHERE huddle_id = $huddle_id");
    $currentNumOfHuddlees =  mysqli_num_rows($getNumberOfHuddlees);
	
	mysqli_close($connect);
return $currentNumOfHuddlees;
}

/*
* returns passcode from huddle
*/
function hasPasscode($huddle_id){
	$connect = mysqli_connect("localhost", "proj6", "Huddle123!!", "proj6");
	$getPass = mysqli_query($connect, "SELECT passcode FROM huddles WHERE huddle_id = '$huddle_id'");
    $passResult = mysqli_fetch_array($getPass);
	$result = $passResult[0];
	mysqli_close($connect);
	if (empty($result)){
		return false;
	}else{
		return true;
	}
}



/*
* returns passcode from huddle
*/
function getPasscode($huddle_id){
	$connect = mysqli_connect("localhost", "proj6", "Huddle123!!", "proj6");
$getPass = mysqli_query($connect, "SELECT passcode FROM huddles WHERE huddle_id = '$huddle_id'");
    $passResult = mysqli_fetch_array($getPass);
	mysqli_close($connect);
return $passResult[0];

}

/*
* returns number of huddlees / limit
*/
function numOfHuddleesPerHuddle($huddle_id){
	$connect = mysqli_connect("localhost", "proj6", "Huddle123!!", "proj6");
	
	$getLimit = mysqli_query($connect, "SELECT capacity FROM huddles WHERE huddle_id = '$huddle_id'");
    $row2 = mysqli_fetch_array($getLimit);
    $limit = $row2[0];

    $getNumberOfHuddlees = mysqli_query($connect,"SELECT huddle_id from memberships WHERE huddle_id = $huddle_id");
    $currentNumOfHuddlees =  mysqli_num_rows($getNumberOfHuddlees);
	
	mysqli_close($connect);
return $currentNumOfHuddlees . "/" . $limit;
}


/*
*return if user is in huddle
*
*/
function isUserInHuddle($huddlee, $huddle_id){
  $connect = mysqli_connect("localhost", "proj6", "Huddle123!!", "proj6");

  $getNumberOfHuddlees = mysqli_query($connect,"SELECT huddle_id from memberships WHERE huddle_id = $huddle_id and huddlee = '$huddlee'");
  $currentNumOfHuddlees =  mysqli_num_rows($getNumberOfHuddlees);

  mysqli_close($connect);

  if($currentNumOfHuddlees <= 0){
    return false;
  }
  else{
    return true;
  }
}

/*
* Delete Huddle and its members
* returns true if successful, otherwise returns string with error message
*
*/

function deleteHuddle($huddleid){
  $connect = mysqli_connect("localhost", "proj6", "Huddle123!!", "proj6");
  $removeMembQuery = "DELETE FROM memberships where huddle_id = $huddleid";
  $removeMainHuddleQuery = "DELETE FROM huddles where huddle_id = $huddleid";
  $membResult = $connect->query($removeMembQuery);
  $mainResult = $connect->query($removeMainHuddleQuery);
  mysqli_close($connect);
  if($mainResult = true && $membResult = true){
    return true;
  }else{
    return false;
  }
}


/*
 * join huddle
 * returns true is successful, otherwise returns string with error message
 */

function joinHuddle($huddleid, $huddlee){
    $result;
    $connect = mysqli_connect("localhost", "proj6", "Huddle123!!", "proj6");

    $getLimit = mysqli_query($connect, "SELECT capacity FROM huddles WHERE huddle_id = '$huddleid'");
    $row2 = mysqli_fetch_array($getLimit);
    $limit = $row2[0];

    $getNumberOfHuddlees = mysqli_query($connect,"SELECT huddle_id from memberships WHERE huddle_id = $huddleid");
    $currentNumOfHuddlees =  mysqli_num_rows($getNumberOfHuddlees);

    if($limit != "Unlimited"){
      if ($currentNumOfHuddlees >= $limit){
        return false;
      }
      else{
        $query = "INSERT INTO memberships (`huddle_id`, `huddlee`) VALUES ('$huddleid', '$huddlee')";
        $result = $connect->query($query);
        mysqli_close($connect);
      }
    }
    else{
      $query = "INSERT INTO memberships (`huddle_id`, `huddlee`) VALUES ('$huddleid', '$huddlee')";
      $result = $connect->query($query);
      mysqli_close($connect);
    }
    return $result;
}

/*
 * Builds and attempts to insert a new Huddle into the Huddles table
 * Returns true if successful
 * $host : the Huddlee hosting the Huddle
 * $name : the name of the Huddle
 * $description : the description of the Huddle
 * $capacity : the capacity of the Huddle
 * $date : the datetime of the Huddle
 * $img : the image of the Huddle
 * $passcode : the passcode of the Huddle
 */
function createHuddle($host, $name, $description, $capacity, $date, $img=null, $passcode=null){
    // connect to database
    $conn = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");

    // build query
    $query = "INSERT INTO huddles (`host`, `name`, `description`, `passcode`, `img`, `date_created`, `date_of`, `capacity`)
                           VALUES ('$host', '$name', '$description', '$passcode', '$img', 'NOW()', '$date', $capacity)";

    // execute query
    $result = $conn->query($query);

    // close database connection
    mysqli_close($conn);

    // return result
    return $result;

}


//top level return user function.
function returnUser(){
    session_start();
    $_SESSION['userlogin'] = "vance";
    $user = $_SESSION['userlogin'];

    return $user;

}

//call function here to test. Test is called within the function
//you want to test.

//return users huddles!
function returnUserHuddles(){

    $user = returnUser();
        $mysqli = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");
        $query = "SELECT * FROM huddles WHERE host = '$user'";
        $result = $mysqli->query($query);

    return $result;


}

//Test function
function test($result){

    if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["host"] . "<br>";
    }
} else {
    echo "0    results";
}

}


?>
