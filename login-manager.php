<?php 

function logMeIn($usernamelogin, $passlogin){
    
           /**
        Requires longHandling.php for the log system.
        Requires db-con for database connection
        **/
        require_once("db-con.php");
        require_once("logHandling.php");
 
        //uncomment this to turn the login system back on. It automatically redirects.
        //header('Location: hardware_info.php');

        /**
        Checks to see if server method is post.
        **/
           

        $usernamelogin = mysqli_real_escape_string($mysqli, $usernamelogin); //sanitized here
        $passlogin = mysqli_real_escape_string($mysqli, $passlogin);
           
           
        $result = $mysqli->query("SELECT * FROM huddlees where username = '$usernamelogin' LIMIT 1"); //Searchs for username.
        /**
        This query uses the log system to determine the number of logins by the username in the last 5 minutes.
        **/
        $loginAttempts = mysqli_num_rows($mysqli->query("SELECT * FROM loginsystem WHERE username = '$usernamelogin' and event = 'Login Failure - incorrect password' and (datetime > now() - INTERVAL 1 MINUTE)"));
        
        
           $rowcount = mysqli_num_rows($result); //gets the row count of $result to make sure it found a valid username
           
            if($rowcount > 0) //checks here
            {
                if($loginAttempts <= 5) //checks for a maximum of 5 in the last 5 minutes
                {
        
                while ($card = mysqli_fetch_row($result)) //turns result into an array ($card) so the data is easy to work with.
                {

			     $user = $card[0];
			         if ($usernamelogin == $user) 
                        {
                
                            $password = $card[1];   //variables are spots in the array. 
                            $permission = $card[2];
                            $salt = $card[3];
                            $userentry = hash('sha256', $passlogin);
                            $hashed = hash('sha512', $userentry . $salt);
                
                            if($hashed == $password)
                                {
                                    newLogLogin($user, "Login Success"); //logs user login sucess
                                    session_start(); 
                                    $_SESSION['userlogin'] = $user;     //sets login tokens
                                    $_SESSION['idlevel'] = $permission;
                                    header('Location: Homepage/homepage.php'); //redirects to hardware info page.

                
                                }
                
                            else 
                                {

                                    newLogLogin($user, "Login Failure - incorrect password");  //logs incorect password attempt
                                    return "Username or Password incorrect";   //displays vague message because security              
                
                                }
            
                        }
               
                }
                
                }
                
                else 
                { 
                    return "Max amount of login attempts in 5 minutes exceeded. Please wait 5 minutes and try again.";                    
                    newLogLogin($usernamelogin, "Login Attempts Exceeded");  //logs a login attempt after maximum amount of logins (5) in 5 minutes is exceeded.

                }
       
            }
           
            else
                {
                    return "Username or Password incorrect";  //logs unknown account and displays vague error message.
                    newLogLogin($usernamelogin, "Login Failure - Unknown Account");
                }
       
       }    
    
    



?>