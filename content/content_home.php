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
} else {
    $email = "";
}
?>

<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="assets/images/login logo.png" class="img-fluid" alt="Phone image">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <form method="POST">
                <!-- Email input -->
                <div class="form-outline mb-4">
                    <input type="email" name="email" id="form1Example13" value="<?php $email ?>" class="form-control form-control-lg" placeholder="Je email adres" />
                    <label class="form-label" for="form1Example13">Email address</label>
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                    <input type="password" name="password" placeholder="Je wachtwoord" id="form1Example23" class="form-control form-control-lg" />
                    <label class="form-label" for="form1Example23">Password</label>
                </div>
                <?php
                if(count($errors) > 0){
                    echo "<ul class=\"errors\">";
                        for ($i=0; $i < count($errors); $i++) { 
                            echo "<li>".$errors[$i]."</li>";
                        }
                    echo "</ul>";
                }
                ?>
                <!-- Submit button -->
                <div class="row">
                    <button type="submit" name="submit" value="inloggen" class="btn btn-primary btn-lg btn-block">Login</button>
                    <!-- <a type="submit" name="submit" value="registreer" class="btn btn-primary btn-lg btn-block">Register</button> -->
                    <a href="?page=2" value="registreer" class="btn btn-secondary btn-lg btn-block mt-5">Registreren</a>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>