<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

// Vérifier si l'ID de l'utilisateur est fourni
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Récupérer les informations de l'utilisateur
    $sql = "SELECT * FROM table_user WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupérer le rôle actuel de l'utilisateur
    $sql_role = "SELECT r.role_id, r.role_name
                 FROM table_role_user ru
                 JOIN table_role r ON ru.role_id = r.role_id
                 WHERE ru.user_id = :user_id";
    $stmt_role = $db->prepare($sql_role);
    $stmt_role->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_role->execute();
    $current_role = $stmt_role->fetch(PDO::FETCH_ASSOC);

    // Récupérer la liste des rôles
    $sql_roles = "SELECT role_id, role_name FROM table_role";
    $stmt_roles = $db->prepare($sql_roles);
    $stmt_roles->execute();
    $roles = $stmt_roles->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Rediriger vers la liste des utilisateurs si aucun ID n'est fourni
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer l'utilisateur</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Éditer l'utilisateur</h1>
    </header>

    <div class="container">
        <form action="process.php" method="post">
            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

            <label for="user_nom">Nom :</label>
            <input type="text" name="user_nom" id="user_nom" value="<?= $user['user_nom'] ?>" required>

            <label for="user_prenom">Prénom :</label>
            <input type="text" name="user_prenom" id="user_prenom" value="<?= $user['user_prenom'] ?>" required>

            <label for="user_tel">Téléphone :</label>
            <input type="tel" name="user_tel" id="user_tel" value="<?= $user['user_tel'] ?>" required>

            <label for="user_mail">Email :</label>
            <input type="email" name="user_mail" id="user_mail" value="<?= $user['user_mail'] ?>" required>

            <label for="role">Rôle :</label>
            <select name="role_id" id="role">
                <option value="">Sélectionner un rôle</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['role_id'] ?>" <?= ($current_role && $current_role['role_id'] == $role['role_id']) ? 'selected' : '' ?>>
                        <?= $role['role_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>
</body>

</html>