<?php
$showForm = true;
$errors = array();

if(isset($_POST["submit"])){
    $email = strtolower(trim(strip_tags($_POST["email"])));
    $password = trim(strip_tags($_POST["password"]));

    if(strlen($email) < 1){
        array_push($errors, "Vul een email adres in om in te loggen.");
    }

    if(strlen($password) < 8){
        array_push($errors, "Je wachtwoord dient minimaal 8 tekens te zijn.");
    }

    if(!count($errors) > 0){
        // Validate
        $sql = "SELECT * FROM user WHERE email='".$email."' ORDER BY id LIMIT 1";
        if($result = $db->prepare($sql)){
            $result->execute();
            if($result->rowCount() == 1){
                $result = $result->fetchAll(PDO::FETCH_ASSOC);

                if(password_verify($password, $result[0]["password"])){
                    // Correct password, login
                    login($result[0]["id"]);
                    header("Refresh:0");
                } else {
                    array_push($errors, "Uw wachtwoord is onjuist.");
                }
            } else {
                array_push($errors, "Er is geen account gevonden met deze gegevens.");
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
        echo "<input type=\"email\" name=\"email\" placeholder=\"Je email adres\" autofocus>";
        echo "<input type=\"password\" name=\"password\" placeholder=\"Je wachtwoord\">";
        echo "<input name=\"submit\" type=\"submit\" value=\"Inloggen\">";
    echo "</form>";

    echo "<a href=\"?page=2\">Account aanmaken</a>";
}
