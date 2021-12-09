<?php
    if(isset($_POST["cancelRequest"])){
        deleteFriendRequest($db, $_SESSION["userId"], $_POST["profileId"]);
    }

    if(isset($_POST["acceptRequest"])){
        addFriend($db, $_SESSION["userId"], $_POST["profileId"]);
    }

    echo "<h1>".$pageTitle."</h1>";
    echo "<p>".$pageContent."</p>";

    echo "<div class=\"row\">";
        echo "<div class=\"col-md mb-5\">";
            echo "<h3>Vriendschapsverzoeken</h3>";
            $requests = getPendingRequests($db, $_SESSION["userId"]);
            if(count($requests) > 0){
                foreach($requests as $user){
                    $row = getUserData($db, $user);
                    echo "<div class=\"row\">";
                        echo "<div class=\"col-md\">";
                            if(strlen($row["profilePicUrl"]) < 1){
                                echo "<img class=\"profilePicture circle smaller\" src=\"assets/images/nopicture_".$row["gender"].".png\" alt=\"Profielfoto van ".$row["firstName"]." ".$row["lastName"]."\">";
                            } else {
                                echo "<img class=\"profilePicture circle smaller\" src=\"uploads/".$row["profilePicUrl"]."\" alt=\"Profielfoto van ".$row["firstName"]." ".$row["lastName"]."\">";
                            }
                        echo "</div>";
                        echo "<div class=\"col-md\">";
                            if(checkIfPendingFriendRequest($db, $user, $_SESSION["userId"])){
                                echo "<form method=\"POST\">";
                                    // logged in user has request from target (option to accept or decline)
                                    echo "<input type=\"hidden\" name=\"profileId\" value=\"".$user."\">";
                                    echo "<input type=\"submit\" class=\"btn-dark\" name=\"acceptRequest\" value=\"Vriendschapsverzoek accepteren\">";
                                    echo "<input type=\"submit\" class=\"btn-dark\" name=\"declineRequest\" value=\"Vriendschapsverzoek weigeren\">";
                                echo "</form>";
                            }
                        echo "</div>";
                    echo "</div>";
                }
               
            }
        echo "</div>";
    echo "</div>";

    echo "<div class=\"row\">";
        echo "<div class=\"col-md mb-5\">";
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

                $sql = "SELECT id, firstName, lastName, profilePicUrl, gender FROM user WHERE CONCAT(firstName,' ',lastName)=?";
                if($result=$db->prepare($sql)){
                    $result->execute([$sStr]);
                    if($result->rowCount() > 0){
                        $result = $result->fetchAll(PDO::FETCH_ASSOC);

                        echo "<ul>";
                            foreach($result as $row){
                                echo "<li>";
                                    if(strlen($row["profilePicUrl"]) < 1){
                                        echo "<img class=\"profilePicture circle smaller\" src=\"assets/images/nopicture_".$row["gender"].".png\" alt=\"Profielfoto van ".$row["firstName"]." ".$row["lastName"]."\">";
                                    } else {
                                        echo "<img class=\"profilePicture circle smaller\" src=\"uploads/".$row["profilePicUrl"]."\" alt=\"Profielfoto van ".$row["firstName"]." ".$row["lastName"]."\">";
                                    }
                                    
                                    echo "<a href=\"?profileId=".$row["id"]."\">".$row["firstName"]." ".$row["lastName"]."</a>";
                                echo "</li>";
                            }
                        echo "</ul>";
                    } else {
                        echo "<p>Er zijn geen gebruikers gevonden.</p>";
                    }
                }
            }
        echo "</div>";
    echo "</div>";
    echo "<div class=\"row\">";
        echo "<div class=\"col-md mb-5\">";
            echo "<h3>Gemeenschappelijke vrienden</h3>";

            $commonFriendIds = getCommonFriends($db, $_SESSION["userId"]);
            if(count($commonFriendIds) > 0){
                echo "<ul class=\"userList list-unstyled\">";
                    for($i = 0; $i < count($commonFriendIds); $i++){
                        $userData = getUserData($db, $commonFriendIds[$i]);
                        echo "<li>";
                            if(strlen($userData["profilePicUrl"]) < 1){
                                echo "<img class=\"profilePicture circle smallest\" src=\"assets/images/nopicture_".$userData["gender"].".png\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
                            } else {
                                echo "<img class=\"profilePicture circle smallest\" src=\"uploads/".$userData["profilePicUrl"]."\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
                            }
                            
                            echo "<a href=\"?profileId=".$userData["id"]."\">".$userData["firstName"]." ".$userData["lastName"]."</a>";
                        echo "</li>";
                    }
                echo "</ul>";
            } else {
                echo "<p>Er zijn geen gebruikers gevonden.</p>";
            }
        echo "</div>";
    echo "</div>";
?>