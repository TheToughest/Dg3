<?php
$userData = getUserData($db, $_SESSION["userId"]);
echo "<h1>Welkom terug ".$userData["firstName"]." ".$userData["lastName"]."!</h1>";
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
        echo "<input name=\"postSubmit\" type=\"submit\" value=\"Plaatsen\">";
    echo "</form>";
}

$sql = "SELECT id FROM post ORDER BY postDate DESC";
if($result = $db->prepare($sql)){
    $result->execute();
    if($result->rowCount() > 0){
        $result = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $post){
            $p = new Post($db, $post["id"]);
            $p->render();
        }
    }
}
?>