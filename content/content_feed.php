<?php
$userData = getUserData($db, $_SESSION["userId"]);
echo "<h1>Welkom terug ".$userData["firstName"]." ".$userData["lastName"]."!</h1>";
echo "<a href=\"?page=4\">Uitloggen</a>";
$showForm = true;
$errors = array();

if(isset($_POST["submit"])){
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

                header("Location: index.php");
            }
        }
    }
}

if($showForm){
    if(count($errors) > 0){
        echo "<ul class=\"errors\">";
            for ($i=0; $i < count($errors); $i++) { 
                echo "<li>".$errors[$i]."</li>";
            }
        echo "</ul>";
    }
    echo "<form method=\"POST\">";
        echo "<textarea rows=\"5\" cols=\"40\" placeholder=\"Wat ben je aan het doen?\" name=\"content\"></textarea>";
        echo "<input name=\"submit\" type=\"submit\" value=\"Plaatsen\">";
    echo "</form>";
}

$sql = "SELECT * FROM post ORDER BY postDate DESC";
if($result = $db->prepare($sql)){
    $result->execute();
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
?>