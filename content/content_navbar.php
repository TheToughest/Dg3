<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-0 m-0">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/images/login logo.png" width="50" height="50" alt="logo" class="p-0 m-0">
            FakeBook
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <?php echo "<a class=\"nav-link btn btn-dark\" href=\"index.php?profileId=" . $_SESSION["userId"] . "\">Profiel</a>"; ?>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-dark" href="index.php?page=7">Ontdekken</a>
                </li>
                <li class="nav-item" id="mobile">
                    <a class="nav-link btn btn-dark" href="index.php?page=4">Uitloggen</a>
                </li>
            </ul>
        </div>
        <a id="desktop" class="nav-link btn btn-dark float-right" href="index.php?page=4">Uitloggen</a>
    </div>
</nav>