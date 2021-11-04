<?php
if(isset($_GET["profileId"]) && $_GET["profileId"] > 0){
    $userData = getUserData($db, $_GET["profileId"]);

    if($userData != null){
        if(strlen($userData["profilePicUrl"]) < 1){
            echo "<img src=\"assets/images/nopicture_".$userData["gender"].".png\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
        }
    
        echo "<h3>".$userData["firstName"]." ".$userData["lastName"]."</h3>";

        echo "<a href=\"?page=6\">Je profiel bewerken</a>";
    
    
        // Get posts
        $sql = "SELECT * FROM post WHERE userId=? ORDER BY postDate DESC";
        if($result = $db->prepare($sql)){
            $result->execute([intval($_GET["profileId"])]);
            if($result->rowCount() > 0){
                $result = $result->fetchAll(PDO::FETCH_ASSOC);
    
                foreach($result as $post){
                    $post_content = $post["content"];
                    $post_date = $post["postDate"];
                    $post_userId = $post["userId"];
                    $post_user_fullName = "";
    
                    $sql = "SELECT firstName, lastName FROM user WHERE id='".$post_userId."' LIMIT 1";
                    if($result = $db->prepare($sql)){
                        $result->execute();
                        if($result->rowCount() == 1){
                            $result = $result->fetchAll(PDO::FETCH_ASSOC);
                            $result = $result[0];
    
                            $post_user_fullName = $result["firstName"] . " " . $result["lastName"];
    
                            
                            echo "<div class=\"post\">";
                                echo "<a href=\"?profileId=".$post_userId."\"><strong>".$post_user_fullName."</strong></a>";
                                echo "<span class=\"date\">".$post_date."</span>";
                                echo "<p>".$post_content."</p>";
                            echo "</div>";
                        }
                    }
    
                }
            }
        }
    } else {
        echo "<h2>Profiel niet gevonden</h2>";
        echo "<p>Het opgevraagde profiel is niet gevonden, of bestaat niet (meer). Klik <a href=\"index.php\">hier</a> om naar de homepagina te gaan</p>";
    }

    
}
?>