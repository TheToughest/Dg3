<?php
if(isset($_GET["profileId"]) && $_GET["profileId"] > 0){
    $userData = getUserData($db, $_GET["profileId"]);

    if(strlen($userData["profilePicUrl"]) < 1){
        echo "<img src=\"assets/images/nopicture_".$userData["gender"].".png\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
    }

    echo "<h3>".$userData["firstName"]." ".$userData["lastName"]."</h3>";
}
?>