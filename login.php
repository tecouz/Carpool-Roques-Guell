<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$errorMessage = "";

if (isset($_POST['user_mail']) && isset($_POST['user_mot_de_passe'])) {
    $errorMessage = "Identifiants invalides";

    $sql = "SELECT u.*, r.role_name
            FROM table_user u
            LEFT JOIN table_role_user ru ON u.user_id = ru.user_id
            LEFT JOIN table_role r ON ru.role_id = r.role_id
            WHERE u.user_mail = :user_mail";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_mail', $_POST['user_mail']);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($_POST['user_mot_de_passe'], $row["user_mot_de_passe"])) {
            session_start();
            $_SESSION['user_connected'] = "ok";
            $_SESSION['user_id'] = $row["user_id"];
            $_SESSION['user_name'] = $row["user_prenom"];

            // Vérifier le rôle de l'utilisateur et rediriger en conséquence
            if ($row['role_name'] == 'admin') {
                header("Location: /admin/index.php");
            } elseif ($row['role_name'] == 'user') {
                header("Location: /user/index.php");
            } else {
                // Rediriger vers une page par défaut si l'utilisateur n'a pas de rôle spécifique
                header("Location: /default.php");
            }
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