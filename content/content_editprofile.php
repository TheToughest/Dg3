<?php
$showForm = true;
$errors = array();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
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

                    if (file_exists($_FILES['profilePicture']['tmp_name']) || is_uploaded_file($_FILES['profilePicture']['tmp_name'])){
                        $uploaddir = 'uploads/';
                        $uploadfile = $uploaddir . basename($_FILES['profilePicture']['name']);
    
                        $allowed = array('png', 'jpg');
                        $filename = $_FILES['profilePicture']['name'];
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $succes = false;
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

                    if(count($errors) == 0 && $succes == true){
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

                        $sql = "UPDATE user SET firstName='".$firstName."',
                        lastName='".$lastName."',
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
                    }

                    

                    if($succes == true){
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
    $firstName = "";
    $lastName = "";
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

    $sql = "SELECT firstName, lastName, gender, birthdate, country, email, profileFont, profileColor, biography FROM user WHERE id=".$_SESSION["userId"]." LIMIT 1";
    if($result = $db->prepare($sql)){
        $result->execute();
        if($result->rowCount() > 0){
            $result = $result->fetchAll(PDO::FETCH_ASSOC);
            $result = $result[0];

            $firstName = $result["firstName"];
            $lastName = $result["lastName"];
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
?>

<?php
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
?>
<form method="POST" enctype="multipart/form-data">

    <!-- zodat de autocomplete van browsers niet dat wachtwoord op de verkeerde plek plaatst: -->
    <input style="display:none">
    <input type="password" style="display:none">

    <div class="card mb-2">
        <div class="row g-3 p-2">
            <h2>Profiel bewerken</h2>
            <div class="form-group">
                <label for="Profielkleur" class="form-label">Profielkleur</label>
                <input type="color" class="form-control form-control-color" id="Profielkleur" name="profileColor" value="<?php echo $profileColor;?>" placeholder="Profiel kleur" title="Kies je profielkleur" required>
            </div>
            <div class="form-group">
                <label for="ProfielFoto">Profielfoto</label>
                <input type="file" class="form-control-file" name="profilePicture" accept="image/*\" id="ProfielFoto">
            </div>
            <div class="form-group">
                <?php
                    $fontOptions = array("Roboto", "Lato", "Open Sans", "Poppins");
                    echo "<label for=\"lettertype\">Profiel lettertype</label>";
                    echo "<select id=\"lettertype\" name=\"profileFont\" placeholder=\"Profiel lettertype\" class=\"form-control\" required>";
                        for($i = 0; $i < count($fontOptions); $i++){
                            if(strtolower($profileFont) == strtolower($fontOptions[$i]))
                                echo "<option value=\"".$fontOptions[$i]."\" selected>".$fontOptions[$i]."</option>";
                            else
                                echo "<option value=\"".$fontOptions[$i]."\">".$fontOptions[$i]."</option>";
                        }
                    echo "</select>";
                ?>
            </div>
            <div class="form-group">
                <label for="Biografie">Biografie</label>
                <textarea class="form-control" id="Biografie" rows="3" placeholder="Biografie" name="biography" style="resize:none;"><?php echo $biography; ?></textarea>
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <div class="row g-3 p-2">
            <h2>Accountgegevens bewerken</h2>
            <div class="col-md-6">
                <label for="inputVoornaam" class="form-label">Voornaam</label>
                <input type="name" name="firstName" value="<?php echo $firstName; ?>" placeholder="Voornaam" class="form-control" id="inputVoornaam" required>
            </div>
            <div class="col-md-6">
                <label for="inputAchternaam" class="form-label">Achternaam</label>
                <input type="name" name="lastName" value="<?php echo $lastName; ?>" placeholder="Achternaam" class="form-control" id="inputAchternaam" required>
            </div>
            <fieldset class="row mb-1 mt-3">
                <legend class="col-form-label col-sm-2 pt-0">Geslacht</legend>
                <div class="col-sm-10">
                    <?php
                        $genderOptions = array("Man", "Vrouw");
                        for($i = 0; $i < count($genderOptions); $i++){
                            echo "<div class=\"form-ckeck\">";
                            if($gender == $i){
                                echo "<input type=\"radio\" class=\"form-check-input\" name=\"gender\" id=\"gender_".$i."\" value=\"".$i."\" checked required>";
                            } else {
                                echo "<input type=\"radio\" class=\"form-check-input\" name=\"gender\" id=\"gender_".$i."\" value=\"".$i."\" required>";
                            }
                            echo "<label class=\"form-check-label\" for=\"gender_".$i."\">".$genderOptions[$i]."</label>";
                            echo "</div>";
                        }
                    ?>
                </div>
            </fieldset>
            <div class="col-12">
                <label for="inputEmail" class="form-label">Email adres</label>
                <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" id="inputEmail" placeholder="Email adres" required>
            </div>
            <div class="col-md-6">
                <label for="inputLand" class="form-label">Land</label>
                <input type="text" name="country" value="<?php echo $country;?>" class="form-control" placeholder="Land" id="inputLand" required>
            </div>
            <div class="col-md-6">
                <label for="inputGeboortedatum" class="form-label">Geboortedatum</label>
                <input type="date" class="form-control" name="birthdate" value="<?php echo $birthdate;?>" placeholder="Geboortedatum" id="inputGeboortedatum" required>
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <div class="row g-3 p-2">
            <h2>Wachtwoord veranderen</h2>
            <p class="text-muted mt-0 mb-2">Laat de onderstaande velden leeg als je geen nieuw wachtwoord wilt instellen</p>
            <div class="col-md-6">
                <label for="Nieuwwachtwoord" class="form-label">Nieuw wachtwoord</label>
                <input type="password" name="newpassword" placeholder="Nieuw wachtwoord" class="form-control" id="Nieuwwachtwoord">
            </div>
            <div class="col-md-6">
                <label for="Nieuwwachtwoord2" class="form-label">Nieuw wachtwoord bevestigen</label>
                <input type="password" name="newpassword2" placeholder="Nieuw wachtwoord bevestigen" class="form-control" id="Nieuwwachtwoord2">
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <div class="row g-3 p-2">
            <h2>Gegevens opslaan</h2>
            <p class="text-muted mt-0 mb-2">Vul je wachtwoord in om je gegevens op te slaan</p>
            <div class="col-md-6">
                <label for="wachtwoord" class="form-label">Huidige wachtwoord</label>
                <input type="password" name="password" placeholder="Huidige wachtwoord" class="form-control" id="wachtwoord" required>
            </div>
            <div class="col-12">
                <button type="submit" name="submit" value="Opslaan" class="btn btn-primary">Opslaan</button>
                <a href="javascript:history.go(-1)" class="btn btn-secondary">Terug</a>
            </div>
        </div>
    </div>
</form>

<?php
}
?>