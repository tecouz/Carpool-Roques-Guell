<?php
// Connexion à la base de données
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $user_nom = $_POST['user_nom'];
    $user_prenom = $_POST['user_prenom'];
    $user_tel = $_POST['user_tel'];
    $user_mail = $_POST['user_mail'];
    $user_mot_de_passe = $_POST['user_mot_de_passe'];

    // Hasher le mot de passe
    $user_mot_de_passe_hash = password_hash($user_mot_de_passe, PASSWORD_DEFAULT);

    // Requête SQL pour insérer les données dans la table
    $sql = "INSERT INTO table_user (user_nom, user_prenom, user_tel, user_mail, user_mot_de_passe)
    VALUES (:user_nom, :user_prenom, :user_tel, :user_mail, :user_mot_de_passe_hash)";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':user_nom', $user_nom);
    $stmt->bindParam(':user_prenom', $user_prenom);
    $stmt->bindParam(':user_tel', $user_tel);
    $stmt->bindParam(':user_mail', $user_mail);
    $stmt->bindParam(':user_mot_de_passe_hash', $user_mot_de_passe_hash);

    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        echo "Erreur: " . $stmt->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Formulaire d'inscription</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/log.css">
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">
</head>

<body>
    <div class="register-container">
        <h1>Inscription</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="user_nom">Nom :</label>
            <input type="text" id="user_nom" name="user_nom" required><br><br>

            <label for="user_prenom">Prénom :</label>
            <input type="text" id="user_prenom" name="user_prenom" required><br><br>

            <label for="user_tel">Téléphone :</label>
            <input type="tel" id="user_tel" name="user_tel" required><br><br>

            <label for="user_mail">Email :</label>
            <input type="email" id="user_mail" name="user_mail" required><br><br>

            <label for="user_mot_de_passe">Mot de passe :</label>
            <input type="password" id="user_mot_de_passe" name="user_mot_de_passe" required><br><br>

            <input type="submit" value="S'inscrire">
        </form>
    </div>
</body>

</html>