<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/include/connect.php";

$errorMessage = "";
if (isset($_POST['login']) && isset($_POST['password'])) {
    $errorMessage = "Erreur identifiant ou mot de passe";
    $sql = "SELECT * FROM table_user WHERE user_mail = :login";
    $stmt = $db->prepare($sql);
    $stmt->execute([":login" => $_POST['login']]);
    if ($row = $stmt->fetch()) {
        if (password_verify($_POST['password'], $row["user_mot_de_passe"])) {
            echo "Bonjour " . $row["user_prenom"];
            session_start();
            $_SESSION['user_connected'] = "ok"; // Pour éviter le hack, il faudrait mettre des valeurs plus complexes
            $_SESSION['user_name'] = $row["admin_name"];
            header("Location:index.php");
            exit(); // Bloque le script 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BD Shop | Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/connexion.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">
</head>

<body>

    <header>
        <div class="login">
            <a href="register/inscription.php">inscription</a>
        </div>
        <h1>Carpool IFA-MNS</h1>
    </header>
    <div class="container">
        <form action="connexion.php" method="post">
            <div class="input">
                <label for="">Identifiant :</label>
                <input type="text">
            </div>
            <div class="input">
                <label for="">Mot de passe :</label>
                <input type="password">
            </div>

            <div class="button"> Validez </div>
            <?php if ($errorMessage != "") { ?>
                <div>
                    <?= $errorMessage; ?>
                </div>
            <?php } ?>
        </form>
    </div>
</body>

</html>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/connexion.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="login">
            <a href="inscription.php">inscription</a>
        </div>
        <h1>Carpool IFA-MNS</h1>
    </header>

    <div class="container">
        <div class="input">
            <label for="">Identifiant :</label>
            <input type="text">
        </div>
        <div class="input">
            <label for="">Mot de passe :</label>
            <input type="password">
        </div>

        <div class="button"> Validez </div>
    </div>
</body>

</html> -->