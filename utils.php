<?php 

   session_start(); 
    $_SESSION['userlogin'] = "admin"; 

returnUserHuddles();

function returnUserHuddles(){
    
 session_start();
    $user = $_SESSION['userlogin'];
    $conn = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");
    $query = "SLECT * FROM huddles WHERE admin_id = 'user'";
    $result = $conn->query($query);
    
    mysqli_close($conn);
    
    return $result;

    
}


function test(){
    
    
    
}

?>
