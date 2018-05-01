<?php
/**
 * Created by PhpStorm.
 * User: Vance Field III
 * Date: 4/30/2018
 * Time: 4:16 AM
 */

echo getLatestHuddleIdByHost('vance');

/**
 * Returns the latest huddle_id for the given $host
 * @param $host a huddle host
 * @return $id the huddle_id of the given $host
 */
function getLatestHuddleIdByHost($host){
    //echo "GETLATESTHUDDLEIDBYHOST!";
    $id = null;
    // must grab the latest huddle_id created by $host in order to add $host to `memberships` table
    // $id = getLatestHuddleIdForHost($host)
    // insert the huddle_id and $host into `memberships` table
    $conn = new mysqli("localhost", "proj6", "Huddle123!!", "proj6");
    $query = "SELECT * FROM huddles WHERE host='$host' ORDER BY date_created DESC LIMIT 1";
    $result = mysqli_query($conn, $query);



    if (mysqli_num_rows($result) > 0)
    {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            // insert the huddle_id and $host into `memberships` table
            $id = $row['huddle_id'];
            //insertNewMembership($id,$host);
        }
    }
    else
    {
        echo "0 results in latestHuddleResult";
    }
    mysqli_close($conn);
    return $id;

}




?>