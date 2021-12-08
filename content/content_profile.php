<?php
if(isset($_GET["profileId"]) && $_GET["profileId"] > 0){
    $userData = getUserData($db, $_GET["profileId"]);

    if($userData != null){
        if(strlen($userData["profilePicUrl"]) < 1){
            echo "<img src=\"assets/images/nopicture_".$userData["gender"].".png\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
        } else {
            echo "<img src=\"uploads/".$userData["profilePicUrl"]."\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
        }
    
        echo "<h3>".$userData["firstName"]." ".$userData["lastName"]."</h3>";

        if($_GET["profileId"] == $_SESSION["userId"])
            echo "<a href=\"?page=6\">Je profiel bewerken</a>";
    
    
        // Get posts
        $sql = "SELECT id FROM post WHERE userId=? ORDER BY postDate DESC";
        if($result = $db->prepare($sql)){
            $result->execute([intval($_GET["profileId"])]);
            if($result->rowCount() > 0){
                $result = $result->fetchAll(PDO::FETCH_ASSOC);
    
                foreach($result as $post){
                    $p = new Post($db, $post["id"]);
                    $p->render();
                }
            }
        }
    } else {
        echo "<h2>Profiel niet gevonden</h2>";
        echo "<p>Het opgevraagde profiel is niet gevonden, of bestaat niet (meer). Klik <a href=\"index.php\">hier</a> om naar de homepagina te gaan</p>";
    }

    
}
?>