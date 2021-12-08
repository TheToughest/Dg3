<?php
    echo "<h1>".$pageTitle."</h1>";
    echo "<p>".$pageContent."</p>";

    echo "<h3>Vrienden zoeken</h3>";
    $sStr = "";
    if(isset($_GET["searchString"])){
        $sStr = trim(strip_tags($_GET["searchString"]));
    }
    echo "<form method=\"GET\" action=\"index.php?page=7\">";
        echo "<input type=\"text\" name=\"searchString\" placeholder=\"Zoekterm...\" value=\"".$sStr."\">";
        echo "<input type=\"submit\" value=\"Zoeken\">";
    echo "</form>";

    if(isset($sStr) && strlen($sStr) > 0){
        echo "<strong>Zoekresultaten voor \"".$sStr."\":</strong>";
    }

    $sql = "SELECT id, firstname, lastname, profilePicUrl, gender FROM user WHERE CONCAT(firstname,' ',lastname)=?";
    if($result=$db->prepare($sql)){
        $result->execute([$sStr]);
        if($result->rowCount() > 0){
            $result = $result->fetchAll(PDO::FETCH_ASSOC);

            echo "<ul>";
                foreach($result as $row){
                    echo "<li>";
                        if(strlen($row["profilePicUrl"]) < 1){
                            echo "<img class=\"profilePicture circle smaller\" src=\"assets/images/nopicture_".$row["gender"].".png\" alt=\"Profielfoto van ".$row["firstname"]." ".$row["lastname"]."\">";
                        } else {
                            echo "<img class=\"profilePicture circle smaller\" src=\"uploads/".$row["profilePicUrl"]."\" alt=\"Profielfoto van ".$row["firstname"]." ".$row["lastname"]."\">";
                        }
                        
                        echo "<a href=\"?profileId=".$row["id"]."\">".$row["firstname"]." ".$row["lastname"]."</a>";
                    echo "</li>";
                }
            echo "</ul>";
        } else {
            echo "<p>Er zijn geen gebruikers gevonden.</p>";
        }
    }


    echo "<h3>Gemeenschappelijke vrienden</h3>";

    $commonFriendIds = getCommonFriends($db, $_SESSION["userId"]);
    if(count($commonFriendIds) > 0){
        echo "<ul>";
            for($i = 0; $i < count($commonFriendIds); $i++){
                $userData = getUserData($db, $commonFriendIds[$i]);
                echo "<li>";
                    if(strlen($row["profilePicUrl"]) < 1){
                        echo "<img class=\"profilePicture circle smaller\" src=\"assets/images/nopicture_".$userData["gender"].".png\" alt=\"Profielfoto van ".$userData["firstname"]." ".$userData["lastname"]."\">";
                    } else {
                        echo "<img class=\"profilePicture circle smaller\" src=\"uploads/".$userData["profilePicUrl"]."\" alt=\"Profielfoto van ".$userData["firstname"]." ".$userData["lastname"]."\">";
                    }
                    
                    echo "<a href=\"?profileId=".$userData["id"]."\">".$userData["firstname"]." ".$userData["lastname"]."</a>";
                echo "</li>";
            }
        echo "</ul>";
    } else {
        echo "<p>Er zijn geen gebruikers gevonden.</p>";
    }
?>