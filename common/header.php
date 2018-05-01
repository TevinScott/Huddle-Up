<?php
/**
 * Created by PhpStorm.
 * User: Scotty
 * Date: 3/31/18
 * Time: 12:24 AM
 * The purpose of this class is to insert this below heaer html into the encapsulating php webpage using "include"
 */

/**
 * if the user is signed in the as specified by the inputted parameter then an additional drop down is displayed
 * @param $isUserSignedIn the answer to weither the user is currently signed in or not
 * @return string returns the html representation of a drop down menu
 */
function includeAccountDropdown($isUserSignedIn){
	
    if($isUserSignedIn){
		session_start();
		$user = $_SESSION['userlogin'];
        $outputHtml = "
            <a class='btn btn-secondary dropdown-toggle' href='#' role='button' id='dropdownMenuLink' 
            data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                " . $user . "
            <span class='caret'></span></a>
        
            <div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>
                <li><a class='dropdown-item' href='../Huddles/my-huddles.php'>My Huddles</a></li>
                <li><a class='dropdown-item' href='../logout.php'>Sign Out</a></li>
            </div>";
    }
    // hrefs need to be connected to login page and log out page
    else {
        $outputHtml = "<a class='btn btn-secondary' href='../index.php' role='button'>
                Login
            <span class=\'caret\'></span></a>
            <a class='btn btn-secondary dropdown-toggle' href='../createAccount.php' role='button'>
                Sign Up 
            <span class=\'caret\'></span></a>";
    }
    return $outputHtml;
}
/**
 * Call this function at the top of every page if the user is specified as signed in the
 * header will display user options.
 * @param bool $isUserSignedIn: The answer to weither the user is currently signed in or not
 */
function displayHeader($isUserSignedIn = false){

    echo "
    <style>",
        include 'header.css',
    "</style>",

    "<nav class='navbar navbar-default'>
        <!-- logo and toggle get grouped for better mobile display -->
        <div class='navbar-header'>
            <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1' aria-expanded='false'>
                <span class='sr-only'>Toggle navigation</span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
            </button>
            <a class='navbar-brand' href='../Homepage/homepage.php'>Huddle Up</a>
        </div>

        
        <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>";
        if($isUserSignedIn){
            echo "<ul class='nav navbar-nav'>
                <li><a href='../Huddles/create-huddle.php'>Create Huddle <span class='sr-only'>(current)</span></a></li>
				<li><a href='../Huddles/EventsPage.php'>View Huddles <span class='sr-only'>(current)</span></a></li>
				<li><a href='../Huddles/my-huddles.php'>My Huddles <span class='sr-only'>(current)</span></a></li>
            </ul>";
        }
        echo "<ul class='navbar-form navbar-right'>",
                includeAccountDropdown($isUserSignedIn),
            "</ul>
        </div>
</nav>";
}
?>



