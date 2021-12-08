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

function newFriendRequest($db, $senderId, $receiverId){
    $sql = "INSERT INTO friend_request (senderId, receiverId) VALUES ('".$senderId."', '".$receiverId."')";
    if($insert = $db->prepare($sql)){
        $insert->execute();
    }
}

function deleteFriendRequest($db, $senderId, $receiverId){
    $sql = "DELETE FROM friend_request WHERE ((senderId='".$senderId."' && receiverId='".$receiverId."') || (receiverId='".$senderId."' && senderId='".$receiverId."')) ";
    if($delete = $db->prepare($sql)){
        $delete->execute();
    }
}

function checkIfPendingFriendRequest($db, $senderId, $receiverId){
    $return = false;
    // Checks if the sender has sent request to receiver
    $sql = "SELECT id FROM friend_request WHERE senderId='".$senderId."' && receiverId='".$receiverId."'";
    if($result = $db->prepare($sql)){
        $result->execute();
        if($result->rowCount() > 0){
            $return = true;
        }
    }

    return $return;
}

function checkIfFriends($db, $userId1, $userId2){
    $return = false;

    $sql = "SELECT id FROM friend WHERE ((userId1='".$userId1."' && userId2='".$userId2."') || (userId1='".$userId2."' && userId2='".$userId1."'))";
    if($result = $db->prepare($sql)){
        $result->execute();
        if($result->rowCount() > 0){
            $return = true;
        }
    }

    return $return;
}

function addFriend($db, $userId1, $userId2){
    $sql = "INSERT INTO friend (userId1, userId2) VALUES ('".$userId1."', '".$userId2."')";
    if($insert = $db->prepare($sql)){
        $insert->execute();
    }

    deleteFriendRequest($db, $userId1, $userId2);
}

function removeFriend($db, $userId1, $userId2){
    $sql = "DELETE FROM friend WHERE ((userId1='".$userId1."' && userId2='".$userId2."') || (userId1='".$userId2."' && userId2='".$userId1."'))";
    if($delete = $db->prepare($sql)){
        $delete->execute();
    }
}

function getAllFriendIdsFromUser($db, $userId){
    $returnArray = array();
    $sql = "SELECT userId1, userId2 FROM friend WHERE (userId1 = ".$userId." || userId2 = ".$userId.")";
    if($result = $db->prepare($sql)){
        $result->execute();
        if($result->rowCount() > 0){
            $result = $result->fetchAll(PDO::FETCH_ASSOC);

            foreach($result as $row){
                if($row["userId1"] != $userId){
                    array_push($returnArray, $row["userId1"]);
                } else {
                    array_push($returnArray, $row["userId2"]);
                }
            }
        }
    }

    return $returnArray;
}

function getCommonFriends($db, $userId){
    $returnArray = array();

    $userFriends = getAllFriendIdsFromUser($db, $userId);

    // First we loop trough all friends of user
    for($i = 0; $i < count($userFriends); $i++){
        // Than we get all friends from that user
        $thisUsersFriends = getAllFriendIdsFromUser($db, $userFriends[$i]);
        // Than we loop through those friends to see if they are already friends with logged in user and to see if they are not in the array already to prevent duplicates
        for($j = 0; $j < count($thisUsersFriends); $j++){
            if(!in_array($thisUsersFriends[$j], $userFriends)){
                if(!in_array($thisUsersFriends[$j], $returnArray)){
                    array_push($returnArray, $thisUsersFriends[$j]);
                }
            }
        }
    }

    return $returnArray;
}


?>