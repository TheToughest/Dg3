<?php
if(isset($_GET["profileId"]) && $_GET["profileId"] > 0){
    $userData = getUserData($db, $_GET["profileId"]);

    if($userData != null){
        if(strlen($userData["profilePicUrl"]) < 1){
            echo "<img class=\"profilePicture\" src=\"assets/images/nopicture_".$userData["gender"].".png\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
        } else {
            echo "<img class=\"profilePicture\" src=\"uploads/".$userData["profilePicUrl"]."\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
        }
    
        echo "<h3>".$userData["firstName"]." ".$userData["lastName"]."</h3>";

        if($_GET["profileId"] == $_SESSION["userId"]){
            // profile edit button
            echo "<a href=\"?page=6\" class=\"btn-dark\">Je profiel bewerken</a>";
        } else {
            // Friend button (remove, add, accept, decline);
            if(isset($_POST["cancelRequest"])){
                deleteFriendRequest($db, $_SESSION["userId"], $_GET["profileId"]);
            }

            if(isset($_POST["acceptRequest"])){
                // add friend
                addFriend($db, $_SESSION["userId"], $_GET["profileId"]);
            }

            if(isset($_POST["deleteFriend"])){
                // delete friend
                removeFriend($db, $_SESSION["userId"], $_GET["profileId"]);
            }

            if(isset($_POST["declineRequest"])){
                deleteFriendRequest($db, $_GET["profileId"], $_SESSION["userId"]);
            }

            if(isset($_POST["sendRequest"])){
                newFriendRequest($db, $_SESSION["userId"], $_GET["profileId"]);
            }


            echo "<form method=\"POST\">";
                if(checkIfPendingFriendRequest($db, $_SESSION["userId"], $_GET["profileId"])){
                    // logged in user already sent request (option to cancel)
                    echo "<input type=\"submit\" class=\"btn-dark\" name=\"cancelRequest\" value=\"Vriendschapsverzoek annuleren\">";
                } else if(checkIfPendingFriendRequest($db, $_GET["profileId"], $_SESSION["userId"])){
                    // logged in user has request from target (option to accept or decline)
                    echo "<input type=\"submit\" class=\"btn-dark\" name=\"acceptRequest\" value=\"Vriendschapsverzoek accepteren\">";
                    echo "<input type=\"submit\" class=\"btn-dark\" name=\"declineRequest\" value=\"Vriendschapsverzoek weigeren\">";
                } else if(checkIfFriends($db, $_SESSION["userId"], $_GET["profileId"])){
                    echo "<input type=\"submit\" class=\"btn-dark\" name=\"deleteFriend\" value=\"Vriend verwijderen\">";
                } else {
                    // Not friends, show send request button
                    echo "<input type=\"submit\" class=\"btn-dark\" name=\"sendRequest\" value=\"Vriendschapsverzoek sturen\">";
                }
            echo "</form>";
        }
            
    
    
        // Get posts
        echo "<div class=\"row\">";
            echo "<div class=\"col-md\">";
                echo "<h3>Berichten</h3>";
                $sql = "SELECT id FROM post WHERE userId=? ORDER BY postDate DESC";
                if($result = $db->prepare($sql)){
                    $result->execute([intval($_GET["profileId"])]);
                    if($result->rowCount() > 0){
                        $result = $result->fetchAll(PDO::FETCH_ASSOC);
            
                        foreach($result as $post){
                            $p = new Post($db, $post["id"]);
                            $p->render();
                        }
                    } else {
                        echo "<i>Deze gebruiker heeft geen openbare berichten</i>";
                    }
                }
            echo "</div>";
            echo "<div class=\"col-md\">";
                echo "<h3>Vrienden</h3>";
                $friends = getAllFriendIdsFromUser($db, $_GET["profileId"]);
                if(count($friends) > 0){
                    echo "<ul class=\"userList list-unstyled\">";
                        for($i = 0; $i < count($friends); $i++){
                            $userData = getUserData($db, $friends[$i]);
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
                    echo "<i>Deze gebruiker heeft geen vrienden.</i>";
                }
            echo "</div>";
        echo "</div>";
    } else {
        echo "<h2>Profiel niet gevonden</h2>";
        echo "<p>Het opgevraagde profiel is niet gevonden, of bestaat niet (meer). Klik <a href=\"index.php\">hier</a> om naar de homepagina te gaan</p>";
    }

    
}
?>