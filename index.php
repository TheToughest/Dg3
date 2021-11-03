<?php
session_start();
require("functions.php");
require("db.php");

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
                $pageTitle = $userData["firstName"] . " " . $userData["lastName"];
            break;
        }
        $pageContent = $result["content"];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    echo "<title>" . $pageTitle . " | Fakebook</title>";
    ?>

</head>

<body>
    <?php
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

        }
    ?>
</body>

</html>