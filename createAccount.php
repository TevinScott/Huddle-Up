  <?php
        ini_set('display_errors',true); 
        ini_set('display_startup_errors',true); 
        error_reporting (E_ALL|E_STRICT);  

      


           if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            
			
				$username = $_POST['username'];                    
				$password = $_POST['password'];
				$permission = $_POST['permission'];
                
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $random = '';
                $count = 11;
                for($i = 0; $i < $count; $i++){
            
                    $random .= $characters[rand(0, $charactersLength - 1)];
                    
                }

                $hashedrandom = hash('sha256', $random);
                $hashed = hash('sha512', hash('sha256', $password) . $hashedrandom);

	               require_once("db-con.php");

                $sql = "INSERT INTO huddlees (`username`, `password`, `permission`, `salt`) VALUES ('$username', '$hashed', '$permission', '$hashedrandom')";

                if ($mysqli->query($sql) === TRUE) 
                {
                    //echo "New record created successfully";
					header('Location: Homepage/homepage.php');
                
                    echo '<script type="text/javascript"> window.location = "Homepage/homepage.php"</script>';
                } else 
                {
                    echo "Error: " . $sql . "<br>" . $mysqli->error;
                }
                
                    
                  

			
           }
    
    
            
		?>
<html>
    <style>
        <?php include 'createAccount.css'?>
    </style>
    <head>
		<title>Huddle Up a Create Account</title>
	</head>


    <div class="form" id="creation-panel">
        <h2>Create Huddle Up Account</h2>
        <br>
		<form action='createAccount.php' method='post' name='insert'>
			<input type='text' name='username' placeholder="Username" maxlength="20" required>
			<br/>
            <br/>
            <input type='password' name='password' placeholder="Password" maxlength="20" required>

            <br/>
			<input type='text' name='permission' value='admin' hidden>
            <br/>
			<button class="btn btn-success" type="submit">Create User</button>
            <br/><br/>
            Already have an account? <a href="index.php">Sign in now :D</a>
		</form>
	</div>
</html>