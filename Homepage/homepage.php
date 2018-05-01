<!DOCTYPE html>

<?php
session_start();

if (!isset($_SESSION['userlogin'])) {
    $signedIn = false;
} else {
    $signedIn = true;
}

echo"
<html>
<style>";
include 'homepage-styles.css';
echo "</style>
<body>";

include '../css/bootstrap.php';
include '../common/header.php';
displayHeader($signedIn);
?>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class='jumbotron'>

    <div class='container'>
        <h1 class='display-3'>Welcome To Huddle Up</h1>
    </div>
</div>


<div class='container'>

    <!-- first row -->
    <div class="row">
        <div class="heading">
            <h2 style="font-size: 50px;">What is Huddle Up?</h2>
            <h3>
                Huddle Up brings users together to create and join
                groups ("Huddles"), giving them the opportunity to share ideas and experiences together.
            </h3>
        </div>
    </div>

    <br/>
    <hr>
    <br/>
    <!-- second row -->
    <div class='row'>
        <?php createColumnPanel("../ImageAssets/icons8-add-80.png", "Create new Huddle",
            "Create a new Huddle and connect with other Huddlees to share experiences.", "../Huddles/create-huddle.php",
            "success", $signedIn)?>
        <?php createColumnPanel("../ImageAssets/icons8-eye-80.png", "View all Huddles",
            "View, browse, and join an open Huddle hosted by another user.", '../Huddles/EventsPage.php',
            "info",$signedIn)?>
        <?php createColumnPanel("../ImageAssets/icons8-compose-80.png", "My Huddles",
            "View and browse all of my current and past Huddles.", '../Huddles/my-huddles.php',
            "primary",$signedIn)?>

    </div> <!-- row -->

    <br/>
    <hr>

</div>

</body>



</html>


</body>

</html>

<?php
/**
 * creates a colomn panel for the home page that allows the re use of the general lay out with custom variations
 * @param $iconRef the image location reference for the icon that will be used
 * @param $title the title of the coloumn panel that will be at the top of the div
 * @param $desc a basic description of what this panel does for the user
 * @param $btnRef the link to the location for which this panel's button will redirect the user to
 * @param $type this specifies the type of button this panel contains eg. success, info , primary
 */

function createColumnPanel($iconRef, $title, $desc, $btnRef,$type, $isSignedIn)
{

    echo "<div class='col-md-4'>

            <!-- col icon -->
            <div class='col-icon'>
                <img src=$iconRef>
            </div>

            <!-- col title -->
            <div class='col-title'>
                $title
            </div>

            <!-- col description -->
            <div class='col-desc'>
                $desc
            </div>";

    if ($isSignedIn){
        echo "<!-- col button -->
        <div class='col-button'>
            <button type='button' class='btn btn-$type'
              onclick=window.location.href='$btnRef'>$title</button>
        </div>";
    }

    echo "</div>";

}
