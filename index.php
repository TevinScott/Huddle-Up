<!DOCTYPE html>
<?php require_once("login-manager.php");

       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            $usernamelogin = $_POST["user"]; //username and login from the entry form gathered.
            $passlogin = $_POST["pass"]; 
            $message = logMeIn($usernamelogin, $passlogin); // attempt to login              
           
       }



?>
<html>
    <style>
        <?php include 'createAccount.css'?>
    </style>
	<head>
		<title>Login</title>
	</head>

	<body  id="loginbody" link="#ffffff" vlink="#ffffff" alink="#003a99">

			<br>
            <div class = "form">
                <h2> <?php echo $message ?> </h2>
                <h1>HuddleUp</h1>
                <form action="index.php" method="post" class="form-signin">

                    <h2 class="form-signin-heading">Sign in</h2>

                    <input type="text" name="user" id="userInput" class="form-control" placeholder="Username" maxlength="20" required autofocus>

                    <input type="password" name="pass" id="inputPassword" class="form-control" placeholder="Password" maxlength="20" required>

                    <button class="btn btn-lg btn-success btn-block" type="submit">Sign in</button>
                    Don't have an account? <a href="createAccount.php">Create one!</a>
                </form>
            </div>
	
	</body>
  
</html>