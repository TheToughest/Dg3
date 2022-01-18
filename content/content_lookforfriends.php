<?php
    if(isset($_POST["cancelRequest"])){
        deleteFriendRequest($db, $_SESSION["userId"], $_POST["profileId"]);
    }

    if(isset($_POST["acceptRequest"])){
        addFriend($db, $_SESSION["userId"], $_POST["profileId"]);
    }

    echo "<div class=\"row mt-5\">";
        echo "<div class=\"col-lg-8\">";
            echo "<h3 class=\"mb-3\">Vrienden zoeken</h3>";
            $sStr = "";
            if(isset($_GET["searchString"])){
                $sStr = trim(strip_tags($_GET["searchString"]));
            }
            echo "<form method=\"GET\" action=\"index.php?page=7\" class=\"mb-5 ms-3\">";
                echo "<div class=\"row\">";
                    echo "<input type=\"text\" class=\"form-control w-50\" id=\"inputString\" name=\"searchString\" placeholder=\"Zoekterm...\" value=\"".$sStr."\">";
                    echo "<input type=\"submit\" class=\"btn btn-primary w-25 ms-2\" value=\"Zoeken\">";
                echo "</div>";
            echo "</form>";

            if(isset($sStr) && strlen($sStr) > 0){
                echo "<strong class=\"d-block mb-2\">Zoekresultaten voor \"".$sStr."\":</strong>";

                $sql = "SELECT id, firstName, lastName, profilePicUrl, gender FROM user WHERE (CONCAT(firstName,' ',lastName)=? || firstName=? || lastName=?)";
                if($result=$db->prepare($sql)){
                    $result->execute([$sStr, $sStr, $sStr]);
                    if($result->rowCount() > 0){
                        $result = $result->fetchAll(PDO::FETCH_ASSOC);

                        echo "<ul>";
                            foreach($result as $row){
                                echo "<li class=\"mb-3\">";
                                    if(strlen($row["profilePicUrl"]) < 1){
                                        echo "<img class=\"profilePicture circle smaller\" src=\"assets/images/nopicture_".$row["gender"].".png\" alt=\"Profielfoto van ".$row["firstName"]." ".$row["lastName"]."\">";
                                    } else {
                                        echo "<img class=\"profilePicture circle smaller\" src=\"uploads/".$row["profilePicUrl"]."\" alt=\"Profielfoto van ".$row["firstName"]." ".$row["lastName"]."\">";
                                    }
                                    
                                    echo "<a href=\"?profileId=".$row["id"]."\" class=\"ms-2 text-decoration-none text-dark\">".$row["firstName"]." ".$row["lastName"]."</a>";
                                echo "</li>";
                            }
                        echo "</ul>";
                    } else {
                        echo "<p>Er zijn geen gebruikers gevonden.</p>";
                    }
                }
            }
        echo "</div>";
                        
        $counter = 0;

        echo "<div class=\"col-lg-4\">";
            echo "<div class=\"card p-2 mb-3 personen\">";
                echo "<h3>Vriendschapsverzoeken</h3>";
                $requests = getPendingRequests($db, $_SESSION["userId"]);
                if(count($requests) > 0){
                    foreach($requests as $user){
                        $row = getUserData($db, $user);
                        echo "<div class=\"row my-2\">";
                            echo "<div class=\"col-lg-4 text-center\">";
                                echo "<a class=\"text-decoration-none font-weight-bold text-dark\" href=\"?profileId=".$row["id"]."\">";
                                    if(strlen($row["profilePicUrl"]) < 1){
                                        echo "<img class=\"profilePicture circle smallest me-2\" src=\"assets/images/nopicture_".$row["gender"].".png\" alt=\"Profielfoto van ".$row["firstName"]." ".$row["lastName"]."\">";
                                    } else {
                                        echo "<img class=\"profilePicture circle smallest me-2\" src=\"uploads/".$row["profilePicUrl"]."\" alt=\"Profielfoto van ".$row["firstName"]." ".$row["lastName"]."\">";
                                    }
                                echo "<br>";
                                echo $row["firstName"]." ".$row["lastName"]."</a>";
                            echo "</div>";
                            echo "<div class=\"col-lg-8 d-flex align-items-center\">";
                                if(checkIfPendingFriendRequest($db, $user, $_SESSION["userId"])){
                                    echo "<form method=\"POST\" class=\"\">";
                                        // logged in user has request from target (option to accept or decline)
                                        echo "<input type=\"hidden\" name=\"profileId\" value=\"".$user."\">";
                                        echo "<input type=\"submit\" class=\"btn btn-primary me-2\" name=\"acceptRequest\" value=\"Accepteren\">";
                                        echo "<input type=\"submit\" class=\"btn btn-secondary\" name=\"cancelRequest\" value=\"Weigeren\">";
                                    echo "</form>";
                                }
                            echo "</div>";
                        echo "</div>";
                        if ($counter != (count($requests)-1)) {
                            echo "<hr class=\"mx-2 my-1\">";
                            $counter++;
                        }
                    }
                } else {
                    echo "<p class=\"text-muted\">Je hebt geen inkomende vriendschapsverzoeken.</p>";
                }
            echo "</div>";
            echo "<div class=\"card p-2 personen\">";
                echo "<h3>Vrienden van vrienden</h3>";
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
                    echo "<p class=\"text-muted\">Er zijn geen gebruikers gevonden.</p>";
                }
            echo "</div>";
        echo "</div>";
    echo "</div>";
?>