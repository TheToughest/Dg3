<?php

// Checks if the user is logged in, returns boolean.
function isLoggedIn(){
    $bool = false;
    if(isset($_SESSION["userId"]) && $_SESSION["userId"] > 0){
        $bool = true;
    }

    return $bool;
}

// Logs in the user by its account id
function login($id){
    $_SESSION["userId"] = intval($id);
}

function getUserData($db, $userId){
    $sql = "SELECT * FROM user WHERE id='".$userId."' ORDER BY id LIMIT 1";
    if($result = $db->prepare($sql)){
        $result->execute();
        if($result->rowCount() == 1){
            $result = $result->fetchAll(PDO::FETCH_ASSOC);
            $result = $result[0];
            

            return $result;
        } else {
            return null;
        }
    } else {
        return null;
    }
}

function logout(){
    session_destroy();
    header("Location: index.php");
}
?>