<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

// Récupérer les informations de l'utilisateur à partir de la base de données
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM table_user WHERE user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les valeurs actuelles des champs
$user_nom = $user['user_nom'];
$user_prenom = $user['user_prenom'];
$user_tel = $user['user_tel'];
$user_mail = $user['user_mail'];
$user_image = $user['user_image'];

// Récupérer les préférences de l'utilisateur
$sql = "SELECT preference_id FROM table_user_preference WHERE user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user_preferences = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Récupérer toutes les préférences
$sql = "SELECT * FROM table_preference";
$stmt = $db->prepare($sql);
$stmt->execute();
$preferences = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le profil</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/profile.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <p>
            <a href="../admin/index.php">Accueil</a>
        </p>
        <p>
            <a href="#info">Info</a>
        </p>
        <p>
            <a href="#profile">Votre Profil</a>
        </p>
        <h1>Modifier le profil de <?php echo $_SESSION['user_name']; ?></h1>
    </header>
    <div class="container">
        <form action="process.php" method="post" enctype="multipart/form-data">
            <label for="user_nom">Nom :</label>
            <input type="text" name="user_nom" id="user_nom" value="<?php echo $user_nom; ?>" required>

            <label for="user_prenom">Prénom :</label>
            <input type="text" name="user_prenom" id="user_prenom" value="<?php echo $user_prenom; ?>" required>

            <label for="user_tel">Téléphone :</label>
            <input type="tel" name="user_tel" id="user_tel" value="<?php echo $user_tel; ?>" required>

            <label for="user_mail">Email :</label>
            <input type="email" name="user_mail" id="user_mail" value="<?php echo $user_mail; ?>" required>

            <label for="user_image">Image de profil :</label>
            <input type="file" name="user_image" id="user_image">
            <?php if (!empty($user_image)): ?>
            <p>Image de profil actuelle : <img src="<?php echo $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $user_image; ?>"
                    alt="Image de profil"></p>
            <?php endif; ?>

            <label>Préférences :</label>
            <?php foreach ($preferences as $preference): ?>
            <div>
                <input type="checkbox" name="preferences[]" value="<?php echo $preference['preference_id']; ?>"
                    <?php echo in_array($preference['preference_id'], $user_preferences) ? 'checked' : ''; ?>>
                <label><?php echo $preference['preference_label']; ?></label>
            </div>
            <?php endforeach; ?>

            <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>
</body>

</html>