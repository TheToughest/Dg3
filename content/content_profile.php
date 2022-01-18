<?php
if(isset($_GET["profileId"]) && $_GET["profileId"] > 0){
    $userData = getUserData($db, $_GET["profileId"]);

    if($userData != null){
        echo "<div class=\"card rounded bg-light\">";
            echo "<div class=\"row m-2 ms-0\">";
                echo "<div class=\"col-sm\">";
                    if(strlen($userData["profilePicUrl"]) < 1){
                        echo "<img class=\"profilePicture rounded\" src=\"assets/images/nopicture_".$userData["gender"].".png\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
                    } else {
                        echo "<img class=\"profilePicture rounded\" src=\"uploads/".$userData["profilePicUrl"]."\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
                    }
                echo "</div>";
                
                echo "<div class=\"col-sm text-center\">";
                    echo "<h3>".$userData["firstName"]." ".$userData["lastName"]."</h3>";
                    if (strlen($userData["biography"]) > 0) {
                        echo "<p class=\"text-muted\">" . $userData["biography"] . "</p>";
                    } else {
                        echo "<p class=\"text-muted\">Deze gebruiker heeft nog geen bio ingesteld.</p>";
                    }
                echo "</div>";

                echo "<div class=\"col-sm text-end me-0 p-0\">";
                    if($_GET["profileId"] == $_SESSION["userId"]){
                        // profile edit button
                        echo "<a href=\"?page=6\" class=\"btn-dark mb-2 w-75 text-center\">Je profiel bewerken</a>";
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
                                echo "<input type=\"submit\" class=\"btn-dark mb-2 w-75 text-center\" name=\"cancelRequest\" value=\"Vriendschapsverzoek annuleren\">";
                            } else if(checkIfPendingFriendRequest($db, $_GET["profileId"], $_SESSION["userId"])){
                                // logged in user has request from target (option to accept or decline)
                                echo "<input type=\"submit\" class=\"btn-dark mb-2 w-75 text-center\" name=\"acceptRequest\" value=\"Vriendschapsverzoek accepteren\">";
                                echo "<input type=\"submit\" class=\"btn-dark mb-2 w-75 text-center\" name=\"declineRequest\" value=\"Vriendschapsverzoek weigeren\">";
                            } else if(checkIfFriends($db, $_SESSION["userId"], $_GET["profileId"])){
                                echo "<input type=\"submit\" class=\"btn-dark mb-2 w-75 text-center\" name=\"deleteFriend\" value=\"Vriend verwijderen\">";
                            } else {
                                // Not friends, show send request button
                                echo "<input type=\"submit\" class=\"btn-dark mb-2 w-75 text-center\" name=\"sendRequest\" value=\"Vriendschapsverzoek sturen\">";
                            }
                        echo "</form>";
                    }
                echo "</div>";
            echo "</div>";
        echo "</div>";
            
    
    
        // Get posts
        echo "<div class=\"row\">";
            echo "<div class=\"col-lg-8\">";

                if($_GET["profileId"] == $_SESSION["userId"]){
                    $showForm = true;
                    $errors = array();
                    if(isset($_POST["postSubmit"])){
                        $content = trim(strip_tags($_POST["content"]));
                    
                        if(strlen($content) < 1){
                            array_push($errors, "Je bericht kan niet leeg zijn.");
                        }
                    
                        if(strlen($content) > 2000){
                            array_push($errors, "Je bericht mag maximaal 2000 tekens zijn. Kort het bericht in om te plaatsen.");
                        }
                    
                        if(!count($errors) > 0){
                            // Insert
                            $sql = "INSERT INTO post (userId, content, postDate) VALUES ('".$_SESSION["userId"]."', '".$content."', '".date("Y-m-d H:i:s")."')";
                            if($insert = $db->prepare($sql)){
                                if($insert->execute()){
                                    // Do something
                                }
                            }
                    
                            if (file_exists($_FILES['filename']['tmp_name']) || is_uploaded_file($_FILES['filename']['tmp_name'])){
                                $uploaddir = 'uploads/';
                                $uploadfile = $uploaddir . basename($_FILES['filename']['name']);
                    
                                $allowed = array('png', 'jpg');
                                $filename = $_FILES['filename']['name'];
                                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                                if (!in_array($ext, $allowed)) {
                                    $succes = false;
                                    array_push($errors, "Ongeldig bestandstype.");
                                } else {
                                    if (move_uploaded_file($_FILES['filename']['tmp_name'], $uploadfile)) {
                                        // uploaded
                                        // update db field profilepicurl
                                        $sql = "UPDATE post SET filename='".$filename."' WHERE id=".$db->lastInsertId();
                                        if($update = $db->prepare($sql)){
                                            if(!$update->execute()){
                                                $succes = false;
                                            }   
                                        } else {
                                            $succes = false;
                                        }
                    
                                    } else {
                                        $succes = false;
                                        // echo "Possible file upload attack!\n";
                                    }
                                }
                            }
                    
                            header("Location: index.php?profileId=".$_SESSION["userId"]);
                        }
                    }
    
                    ?>
                        <div class="card my-2">
                            <div class="card-body">
                                <?php
                                if($showForm){
                                    echo "<form method=\"POST\" enctype=\"multipart/form-data\">";
                                        echo "<div class=\"row\">";
    
                                            echo "<label>Bericht plaatsen:</label>";
                                            echo "<textarea class=\"card-text m-2 w-75\" rows=\"5\" placeholder=\"...\" cols=\"40\" name=\"content\" style=\"resize:none;\"></textarea>";
    
                                            echo "<label>Foto:</label>";
                                            echo "<input type=\"file\" name=\"filename\" class=\"form-control w-75 m-2\">";
                                            
                                            echo "<input name=\"postSubmit\" class=\"btn btn-primary w-25 ms-2 my-2\" type=\"submit\" value=\"Plaatsen\">";
                                        echo "</div>";
                                    echo "</form>";
                                    if(count($errors) > 0){
                                        echo "<ul class=\"errors mt-4\">";
                                            for ($i=0; $i < count($errors); $i++) { 
                                                echo "<li>".$errors[$i]."</li>";
                                            }
                                        echo "</ul>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php
    
                }

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
            echo "<div class=\"col-lg-4\">";
                echo "<h3 class=\"text-center\">Vrienden</h3>";
                $friends = getAllFriendIdsFromUser($db, $_GET["profileId"]);
                if(count($friends) > 0){
                    echo  "<div class=\"d-flex justify-content-center\">";
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
                    echo "</div>";
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