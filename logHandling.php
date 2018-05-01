<?php 


function newLogLogin($username, $event){
    
    $mysqli = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");
    $mysqli->query("INSERT INTO loginsystem (`username`, `event`, `datetime`) VALUES ('$username', '$event', CURRENT_TIMESTAMP)");    
}


?>