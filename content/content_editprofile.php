<?php
$showForm = true;
$errors = array();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $firstname = trim(strip_tags($_POST["firstname"]));
    $lastname = trim(strip_tags($_POST["lastname"]));

    if(isset($_POST["gender"])){
        $gender = trim(strip_tags($_POST["gender"]));
    } else {
        $gender = "";
    }
    
    $country = trim(strip_tags($_POST["country"]));
    $birthdate = trim(strip_tags($_POST["birthdate"]));
    $email = strtolower(trim(strip_tags($_POST["email"])));
    $password = trim(strip_tags($_POST["password"]));
    $password2 = trim(strip_tags($_POST["password2"]));

    if(strlen($firstname) < 1){
        array_push($errors, "Vul je voornaam in om door te gaan.");
    }

    if(strlen($lastname) < 1){
        array_push($errors, "Vul je achternaam in om door te gaan.");
    }

    if(strlen($firstname) > 100){
        array_push($errors, "Je voornaam mag niet korter zijn dan 100 tekens, kort deze in om door te gaan.");
    }

    if(strlen($lastname) > 100){
        array_push($errors, "Je achternaam mag niet korter zijn dan 100 tekens, kort deze in om door te gaan.");
    }

    if(strlen($email) < 1){
        array_push($errors, "Vul een email adres in om door te gaan.");
    }

    if(strlen($country) < 1){
        array_push($errors, "Vul je nationaliteit in om door te gaan.");
    }

    if(strlen($birthdate) < 1){
        array_push($errors, "Vul je geboortedatum in om door te gaan.");
    }

    if(strlen($gender) < 1){
        array_push($errors, "Vul je geslacht in om door te gaan.");
    }

    if(strlen($password) < 8){
        array_push($errors, "Je wachtwoord dient minimaal 8 tekens te zijn.");
    }

    if($password != $password2){
        array_push($errors, "De ingevulde wachtwoorden komen niet overeen.");
    }



    if(!count($errors) > 0){
        $sql = "SELECT id FROM user WHERE email='".$email."' ORDER BY id";
        if($result = $db->prepare($sql)){
            $result->execute();
            if($result->rowCount() != 0){
                array_push($errors, "Er bestaat al een account met dit email adres.");
            } else {
                $sql = "INSERT INTO user (firstName, lastName, country, birthdate, gender, email, password) VALUES (
                '".$firstname."', 
                '".$lastname."', 
                '".$country."', 
                '".$birthdate."', 
                '".$gender."', 
                '".$email."', 
                '".password_hash($password, PASSWORD_DEFAULT)."')";

                if($insert = $db->prepare($sql)){
                    if($insert->execute()){
                        login(intval($db->lastInsertId()));
                        header("Location: index.php");
                    } else {
                        array_push($errors, "Er is iets foutgegaan bij het aanmaken van je account. (Foutcode #001)");
                    }
                } else {
                    array_push($errors, "Er is iets foutgegaan bij het aanmaken van je account. (Foutcode #002)");
                }
            }
        }
    }

} else {
    $firstname = "";
    $lastname = "";
    $gender = "";
    $birthdate = "";
    $country = "";
    $email = "";
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

        echo "<h2>Accountgegevens bewerken</h2>";

        echo "<div class=\"form-input\">";
            echo "<label>Voornaam</label>";
            echo "<input type=\"text\" name=\"firstname\" value=\"".$firstname."\" placeholder=\"Voornaam\">";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Achternaam</label>";
            echo "<input type=\"text\" name=\"lastname\" value=\"".$lastname."\" placeholder=\"Achternaam\">";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Geslacht</label>";
            $genderOptions = array("Man", "Vrouw");
            for($i = 0; $i < count($genderOptions); $i++){
                echo "<input type=\"radio\" name=\"gender\" id=\"gender_".$i."\" value=\"".$genderOptions[$i]."\">";
                echo "<label for=\"gender_".$i."\">".$genderOptions[$i]."</label>";
            }
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Geboortedatum</label>";
            echo "<input type=\"date\" name=\"birthdate\" value=\"".$birthdate."\" placeholder=\"Geboortedatum\">";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Land</label>";
            echo "<input type=\"text\" name=\"country\" value=\"".$country."\" placeholder=\"Land\">";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Email adres</label>";
            echo "<input type=\"email\" name=\"email\" value=\"".$email."\" placeholder=\"Email adres\">";
        echo "</div>";

        echo "<p class=\"tooltip\">Laat de onderstaande velden leeg als je geen nieuw wachtwoord wilt instellen</p>";

        echo "<div class=\"form-input\">";
            echo "<label>Nieuw wachtwoord</label>";
            echo "<input type=\"password\" name=\"newpassword\" placeholder=\"Nieuw wachtwoord\">";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Nieuw wachtwoord bevestigen</label>";
            echo "<input type=\"password\" name=\"newpassword2\" placeholder=\"Nieuw wachtwoord bevestigen\">";
        echo "</div>";

        echo "<h2>Profiel bewerken</h2>";

        echo "<div class=\"form-input\">";
            $fontOptions = array("Roboto", "Lato", "Open Sans", "Poppins");
            echo "<label>Profiel lettertype</label>";
            echo "<select name=\"profileFont\" placeholder=\"Profiel lettertype\">";
                for($i = 0; $i < count($fontOptions); $i++){
                    echo "<option value=\"".$fontOptions[$i]."\">".$fontOptions[$i]."</option>";
                }
            echo "</select>";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Profiel kleur</label>";
            echo "<input type=\"color\" name=\"profileColor\" placeholder=\"Profiel kleur\">";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Biografie</label>";
            echo "<textarea placeholder=\"Biografie\"></textarea>";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Profielfoto</label>";
            echo "<input type=\"file\" name=\"profilePicture\">";
        echo "</div>";

        echo "<br><br>";

        echo "<div class=\"form-input\">";
            echo "<label>Huidig wachtwoord</label>";
            echo "<input type=\"password\" name=\"password\" placeholder=\"Huidige wachtwoord\">";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<input name=\"submit\" type=\"submit\" value=\"Opslaan\" class=\"buton\">";
            echo "<a href=\"index.php?profileId=".$_SESSION["userId"]."\" class=\"buton\">Terug</a>";
        echo "</div>";
    echo "</form>";

}

?>