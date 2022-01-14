<?php

session_start();
require("functions.php");
require("db.php");

include("classes/post.class.php");

if(isset($_GET["profileId"]) && $_GET["profileId"] > 0){
    $activePageId = 5;
}

if(isset($_GET["page"]) && $_GET["page"] > 0){
    $activePageId = intval($_GET["page"]);
}

$userData = array();

// Bring user to right pages if get page is not set
if(!isset($activePageId)){
    if(isLoggedIn()){
        // Feed page
        $activePageId = 3;

        if(isset($_GET["searchString"])){
            $activePageId = 7;
        }
    } else {
        // Home page
        $activePageId = 1;
    }
}

if(isset($_GET["profile"]) && is_numeric($_GET["profile"])){
    $activeProfileId = intval($_GET["profile"]);
}

// Get page info from db
$sql = "SELECT * FROM page WHERE id=".$activePageId." ORDER BY id LIMIT 1";
if($result = $db->prepare($sql)){
    $result->execute();
    if($result->rowCount() > 0){
        $result = $result->fetchAll(PDO::FETCH_ASSOC);

        $result = $result[0];

        
        switch($activePageId){
            default:
                $pageTitle = $result["title"];
            break;
            case 5:
                $userData = getUserData($db, $_GET["profileId"]);
                if($userData != null){
                    $pageTitle = $userData["firstName"] . " " . $userData["lastName"];
                } else {
                    $pageTitle = "Profiel niet gevonden";
                }
                
            break;
        }
        $pageContent = $result["content"];
    }
}

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    echo "<title>" . $pageTitle . " | Fakebook</title>";

    $profileColor = "#fff";
    $profileFont = "'Roboto', sans-serif";
    if(isset($_GET["profileId"]) && $_GET["profileId"] > 0){
        // get user styles
        $userData = getUserData($db, $_GET["profileId"]);
        if(strlen($userData["profileFont"]) < 1){
            $profileFont = "'Roboto', sans-serif";
        } else {
            switch(strtolower($userData["profileFont"])){
                case "roboto":
                    $profileFont = "'Roboto', sans-serif";
                break;
                case "lato":
                    $profileFont = "'Lato', sans-serif";
                break;
                case "poppins":
                    $profileFont = "'Poppins', sans-serif";
                break;
                case "open sans":
                    $profileFont = "'Open Sans', sans-serif";
                break;
            }
        }

        if(strlen($userData["profileColor"]) < 1){
            $profileColor = "#f8f9fa";
        } else {
            $profileColor = strtolower($userData["profileColor"]);
        }
    } else {
        $profileColor = "#f8f9fa";
    }

    echo "<style>:root {--profile-color: ".$profileColor."; --profile-font: ".$profileFont.";}</style>";
   
    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="profileCustomization.css">
</head>

<body>
    <?php
        if(isLoggedIn()){
            include_once("content/content_navbar.php");
        }
            
        echo "<div class=\"container contentCntr bg-white pt-2 pb-2\">";
            switch($activePageId){
                // If no page is selected (404)
                default:
                break;

                // Home page if not logged in
                case 1:
                    include_once("content/content_home.php");
                break;

                // Register page
                case 2:
                    include_once("content/content_register.php");
                break;

                // Feed page
                case 3:
                    include_once("content/content_feed.php");
                break;

                case 4:
                    logout();
                break;

                case 5:
                    include_once("content/content_profile.php");
                break;

                case 6:
                    include_once("content/content_editprofile.php");
                break;

                case 7:
                    include_once("content/content_lookforfriends.php");
                break;

            }
        echo "</div>";

        if(isLoggedIn()){
            include_once("content/content_footer.php");
        }
    ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>