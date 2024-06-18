<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$errorMessage = "";

if (isset($_POST['user_mail']) && isset($_POST['user_mot_de_passe'])) {
    $errorMessage = "Identifiants invalides";

    $sql = "SELECT * FROM table_user WHERE user_mail = :user_mail";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_mail', $_POST['user_mail']);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($_POST['user_mot_de_passe'], $row["user_mot_de_passe"])) {
            session_start();
            $_SESSION['user_connected'] = "ok";
            $_SESSION['user_id'] = $row["user_id"];
            $_SESSION['user_name'] = $row["user_prenom"]; // Stockage du nom d'utilisateur dans la session
            header("Location: index.php");
            exit();
        }
    } else {
        echo "Aucun résultat trouvé pour l'email fourni.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de connexion</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/log.css">
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <h1>Connexion</h1>
        <form action="login.php" method="post">
            <div>
                <label for="user_mail">Email :</label>
                <input type="email" name="user_mail" id="user_mail" required>
            </div>
            <div>
                <label for="user_mot_de_passe">Mot de passe :</label>
                <input type="password" name="user_mot_de_passe" id="user_mot_de_passe" required>
            </div>
            <button type="submit">Se connecter</button>
            <?php if ($errorMessage != "") { ?>
                <div class="error-message">
                    <?= $errorMessage; ?>
                </div>
            <?php } ?>
        </form>
    </div>
</body>

</html>