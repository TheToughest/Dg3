<?php
$showForm = true;
$errors = array();

if(isset($_POST["submit"])){
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
?>

<!-- if($showForm){
    if(count($errors) > 0){
        echo "<ul class=\"errors\">";
            for ($i=0; $i < count($errors); $i++) { 
                echo "<li>".$errors[$i]."</li>";
            }
        echo "</ul>";
    }
    echo "<form method=\"POST\">";
        echo "<input type=\"text\" name=\"firstname\" value=\"".$firstname."\" placeholder=\"Voornaam\">";
        echo "<input type=\"text\" name=\"lastname\" value=\"".$lastname."\" placeholder=\"Achternaam\">";
        $genderOptions = array("Man", "Vrouw");
        for($i = 0; $i < count($genderOptions); $i++){
            echo "<input type=\"radio\" name=\"gender\" id=\"gender_".$i."\" value=\"".$genderOptions[$i]."\">";
            echo "<label for=\"gender_".$i."\">".$genderOptions[$i]."</label>";
        }
        echo "<input type=\"date\" name=\"birthdate\" value=\"".$birthdate."\" placeholder=\"Geboortedatum\">";
        echo "<input type=\"text\" name=\"country\" value=\"".$country."\" placeholder=\"Land\">";
        echo "<input type=\"email\" name=\"email\" value=\"".$email."\" placeholder=\"Email adres\">";
        echo "<input type=\"password\" name=\"password\" placeholder=\"Wachtwoord\">";
        echo "<input type=\"password\" name=\"password2\" placeholder=\"Wachtwoord bevestigen\">";
        echo "<input name=\"submit\" type=\"submit\" value=\"Registreren\">";
    echo "</form>";

} -->

<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-xl-6 d-none d-xl-block">
                <img src="assets/images/login logo.png" alt="Sample photo" class="img-fluid"/>
            </div>
            <div class="col-xl-6">
              <div class="card-body p-md-5 text-black">
                <h3 class="mb-5 text-uppercase">Vul hier je gegevens in</h3>

                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                      <input type="text" id="form3Example1m" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example1m">First name</label>
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                      <input type="text" id="form3Example1n" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example1n">Last name</label>
                    </div>
                  </div>
                </div>

                <div class="d-md-flex justify-content-start align-items-center mb-4 py-2">
                  <h6 class="mb-0 me-4">Gender: </h6>
                  <div class="form-check form-check-inline mb-0 me-4">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="femaleGender" value="option1"/>
                    <label class="form-check-label" for="femaleGender">Female</label>
                  </div>

                  <div class="form-check form-check-inline mb-0 me-4">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="maleGender" value="option2"/>
                    <label class="form-check-label" for="maleGender">Male</label>
                  </div>
                </div>
                <div class="form-outline mb-4">
                  <input type="text" id="form3Example97" class="form-control form-control-lg" />
                  <label class="form-label" for="form3Example97">Email ID</label>
                </div>

                <div class="d-flex justify-content-end pt-3">
                  <button type="button" class="btn btn-light btn-lg">Reset all</button>
                  <button type="button" class="btn btn-warning btn-lg ms-2">Submit form</button>
                </div>

              </div>

              <a href="index.php" value="registreer" class="btn btn-primary btn-lg btn-block">Toch inloggen?</a>
            </div>
        </div>
    </div>
</section>