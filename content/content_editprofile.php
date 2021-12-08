<?php
$showForm = true;
$errors = array();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $gender = $_POST["gender"];
    $birthdate = $_POST["birthdate"];
    $country = $_POST["country"];
    $email = $_POST["email"];
    $profileFont = $_POST["profileFont"];
    $profileColor = $_POST["profileColor"];
    $biography = $_POST["biography"];

    $newpassword = $_POST["newpassword"];
    $newpassword2 = $_POST["newpassword2"];
    $password = $_POST["password"];
    // $file = $_POST["file"];

    if(strlen($newpassword) > 0){
        if(strlen($newpassword) < 8){
            array_push($errors, "Je nieuwe wachtwoord moet minimaal 8 tekens zijn.");
        } else {
            if($newpassword != $newpassword2){
                array_push($errors, "Je nieuwe wachtwoord komt niet overeen.");
            }
        }
    }




    if(!count($errors) > 0){
        // first check if password is correct
        $sql = "SELECT password FROM user WHERE id=".$_SESSION["userId"];
        if($result = $db->prepare($sql)){
            $result->execute();
            if($result->rowCount() == 1){
                $result = $result->fetchAll(PDO::FETCH_ASSOC);
                if(password_verify($password, $result[0]["password"])){


                    $succes = true;
                    // Change password if needed
                    if(strlen($newpassword) > 0){
                        if($password != $newpassword){
                            // change password
                            $sql = "UPDATE user SET password='".password_hash($newpassword, PASSWORD_DEFAULT)."' WHERE id=".$_SESSION["userId"];
                            if($update = $db->prepare($sql)){
                                if(!$update->execute()){
                                    $succes = false;
                                }   
                            } else {
                                $succes = false;
                            }
                        }
                    }

                    $sql = "UPDATE user SET firstname='".$firstname."',
                    lastname='".$lastname."',
                    gender='".$gender."',
                    birthdate='".$birthdate."',
                    country='".$country."',
                    email='".$email."',
                    profileFont='".$profileFont."', 
                    profileColor='".$profileColor."', 
                    biography='".$biography."' 
                    WHERE id='".$_SESSION["userId"]."'";
                    if($update2 = $db->prepare($sql)){
                        if(!$update2->execute()){
                            $succes = false;
                        }   
                    } else {
                        $succes = false;
                    }

                    if (file_exists($_FILES['profilePicture']['tmp_name']) || is_uploaded_file($_FILES['profilePicture']['tmp_name'])){
                        $uploaddir = 'uploads/';
                        $uploadfile = $uploaddir . basename($_FILES['profilePicture']['name']);
    
                        $allowed = array('png', 'jpg');
                        $filename = $_FILES['profilePicture']['name'];
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            
                            array_push($errors, "Ongeldig bestandstype.");
                        } else {
                            if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $uploadfile)) {
                                // uploaded
                                // update db field profilepicurl
                                $sql = "UPDATE user SET profilePicUrl='".$filename."' WHERE id=".$_SESSION["userId"];
                                if($update = $db->prepare($sql)){
                                    if(!$update->execute()){
                                        $succes = false;
                                    }   
                                } else {
                                    $succes = false;
                                }
    
                            } else {
                                $succes = false;
                                // echo "Possible file upload attack!\n";
                            }
                        }
                    }


                    

                    if($succes = true){
                        $returnMessage = "Je wijzigingen zijn opgeslagen!";
                    } else {
                        $returnMessage = "Je wijzigingen konden niet worden opgeslagen.";
                    }


                    // Change rest of profile
                } else {
                    array_push($errors, "Je wachtwoord is incorrect.");
                }
            } else {
                array_push($errors, "Je gegevens konden niet worden opgehaald (1)");
            }
        } else {
            array_push($errors, "Je gegevens konden niet worden opgehaald (2)");
        }
        


    }

} else {
    $firstname = "";
    $lastname = "";
    $gender = "";
    $birthdate = "";
    $country = "";
    $email = "";
    $newpassword = "";
    $newpassword2 = "";
    $profileFont = "";
    $profileColor = "";
    $biography = "";
    $profilePicture = "";
    $password = "";

    $sql = "SELECT firstname, lastname, gender, birthdate, country, email, profileFont, profileColor, biography FROM user WHERE id=".$_SESSION["userId"]." LIMIT 1";
    if($result = $db->prepare($sql)){
        $result->execute();
        if($result->rowCount() > 0){
            $result = $result->fetchAll(PDO::FETCH_ASSOC);
            $result = $result[0];

            $firstname = $result["firstname"];
            $lastname = $result["lastname"];
            $gender = $result["gender"];
            $birthdate = $result["birthdate"];
            $country = $result["country"];
            $email = $result["email"];
            $profileFont = $result["profileFont"];
            $profileColor = $result["profileColor"];
            $biography = $result["biography"];

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

    if(isset($returnMessage)){
        echo $returnMessage;
    }

    echo "<form method=\"POST\" enctype=\"multipart/form-data\">";

        echo "<h2>Accountgegevens bewerken</h2>";

        echo "<div class=\"form-input\">";
            echo "<label>Voornaam</label>";
            echo "<input type=\"text\" name=\"firstname\" value=\"".$firstname."\" placeholder=\"Voornaam\" required>";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Achternaam</label>";
            echo "<input type=\"text\" name=\"lastname\" value=\"".$lastname."\" placeholder=\"Achternaam\" required>";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Geslacht</label>";
            $genderOptions = array("Man", "Vrouw");
            for($i = 0; $i < count($genderOptions); $i++){
                if($gender == $i){
                    echo "<input type=\"radio\" name=\"gender\" id=\"gender_".$i."\" value=\"".$i."\" checked required>";
                } else {
                    echo "<input type=\"radio\" name=\"gender\" id=\"gender_".$i."\" value=\"".$i."\" required>";
                }
                    

                echo "<label for=\"gender_".$i."\">".$genderOptions[$i]."</label>";
            }
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Geboortedatum</label>";
            echo "<input type=\"date\" name=\"birthdate\" value=\"".$birthdate."\" placeholder=\"Geboortedatum\" required>";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Land</label>";
            echo "<input type=\"text\" name=\"country\" value=\"".$country."\" placeholder=\"Land\" required>";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Email adres</label>";
            echo "<input type=\"email\" name=\"email\" value=\"".$email."\" placeholder=\"Email adres\" required>";
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
            echo "<select name=\"profileFont\" placeholder=\"Profiel lettertype\" required>";
                for($i = 0; $i < count($fontOptions); $i++){
                    if(strtolower($profileFont) == strtolower($fontOptions[$i]))
                        echo "<option value=\"".$fontOptions[$i]."\" selected>".$fontOptions[$i]."</option>";
                    else
                        echo "<option value=\"".$fontOptions[$i]."\">".$fontOptions[$i]."</option>";
                }
            echo "</select>";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Profiel kleur</label>";
            echo "<input type=\"color\" value=\"".$profileColor."\" name=\"profileColor\" placeholder=\"Profiel kleur\" required>";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Biografie</label>";
            echo "<textarea placeholder=\"Biografie\" name=\"biography\">".$biography."</textarea>";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<label>Profielfoto</label>";
            echo "<input type=\"file\" name=\"profilePicture\" accept=\"image/*\">";
        echo "</div>";
        
        echo "<br><br>";

        echo "<div class=\"form-input\">";
            echo "<label>Huidig wachtwoord</label>";
            echo "<input type=\"password\" name=\"password\" placeholder=\"Huidige wachtwoord\" required>";
        echo "</div>";

        echo "<div class=\"form-input\">";
            echo "<input name=\"submit\" type=\"submit\" value=\"Opslaan\" class=\"buton\">";
            echo "<a href=\"index.php?profileId=".$_SESSION["userId"]."\" class=\"buton\">Terug</a>";
        echo "</div>";
    echo "</form>";

}
