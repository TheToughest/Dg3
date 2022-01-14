<?php
$userData = getUserData($db, $_SESSION["userId"]);
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

?>
<div class="row pt-5 pb-5">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">

                <?php
                if($showForm){
                    echo "<form method=\"POST\">";
                        echo "<div class=\"row\">";
                            echo "<h class=\"card-title\">Wat ben je aan het doen?</h>";
                            echo "<textarea class=\"card-text m-2 w-75\" rows=\"5\" placeholder=\"...\" cols=\"40\" name=\"content\" style=\"resize:none;\"></textarea>";
                            echo "<input name=\"postSubmit\" class=\"btn btn-primary w-25 ms-2\" type=\"submit\" value=\"Plaatsen\">";
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
    </div>
    <div class="col-lg-4">
        <div class="card h-100 d-flex justify-content-center align-items-center">
            <p1 class="card-title">Welkom <b><u><?php echo $userData["firstName"];?></b></u></p1>
            <?php
                echo "<a href=\"index.php?profileId=". $_SESSION["userId"]. "\">";
                if(strlen($userData["profilePicUrl"]) < 1){
                    echo "<img class=\"profilePicture circle medium\" src=\"assets/images/nopicture_".$userData["gender"].".png\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
                } else {
                    echo "<img class=\"profilePicture circle medium\" src=\"uploads/".$userData["profilePicUrl"]."\" alt=\"Profielfoto van ".$userData["firstName"]." ".$userData["lastName"]."\">";
                }
                echo "</a>";
            ?>
            <a href="?page=6" class="btn btn-primary mt-2"><i class="bi bi-pencil-square"></i> Profiel bewerken</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <?php

        $postId = getLatestPostIdFromUser($db, $_SESSION["userId"]);
        if(isset($postId) && is_numeric($postId) && $postId > 0){
            $p = new Post($db, $postId);
            $p->render();
        }
        
        $friends = getAllFriendIdsFromUser($db, $_SESSION["userId"]);
        foreach($friends as $friend){
            $postId = getLatestPostIdFromUser($db, $friend);
            if(isset($postId) && is_numeric($postId) && $postId > 0){
                $p = new Post($db, $postId);
                $p->render();
            }
        }

        ?>
    </div>
    <div class="col-lg-4"></div>
</div>