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

// Récupérer les préférences de l'utilisateur
$sql = "SELECT p.preference_label
        FROM table_preference p
        JOIN table_user_preference up ON p.preference_id = up.preference_id
        WHERE up.user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$preferences = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Récupérer les avis de l'utilisateur
$sql = "SELECT a.avis_commentaire, a.avis_note
        FROM table_avis a
        JOIN table_user_avis ua ON a.avis_id = ua.avis_id
        WHERE ua.user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/profile.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <p><a href="../admin/index.php">Accueil</a></p>
        <p><a href="#info">Info</a></p>
        <p><a href="#profile">Votre Profil</a></p>
        <h1>Profile <?php echo $_SESSION['user_name']; ?></h1>
    </header>
    <div class="container">
        <div class="profile-section">
            <p>Nom/Prénom: <?php echo $user['user_nom'] . ' ' . $user['user_prenom']; ?></p>
            <img src="/upload/<?php echo $user['user_image']; ?>" alt="Image de profil">
        </div>
        <div class="profile-info">
            <p>Téléphone: <?php echo $user['user_tel']; ?></p>
            <p>Email: <?php echo $user['user_mail']; ?></p>
            <p>Préférences :</p>
            <ul>
                <?php foreach ($preferences as $preference): ?>
                <li><?php echo $preference; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <a class="button" href="form.php">Editer</a>
        <a class="button" href="delete.php?user_id=<?php echo $user_id; ?>" onclick="return confirmDelete()">Supprimer
            mon compte</a>
        <div class="avis-section">
            <h2>Avis</h2>
            <?php foreach ($avis as $a): ?>
            <div class="avis">
                <p><?php echo $a['avis_commentaire']; ?></p>
                <p>Note: <?php echo $a['avis_note']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
    function confirmDelete() {
        return confirm("Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.");
    }
    </script>
</body>

</html>