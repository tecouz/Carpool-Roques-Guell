<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

// Récupérer la liste des utilisateurs avec leurs rôles
$sql = "SELECT u.user_id, u.user_nom, u.user_prenom, u.user_tel, u.user_mail, r.role_name
        FROM table_user u
        LEFT JOIN table_role_user ru ON u.user_id = ru.user_id
        LEFT JOIN table_role r ON ru.role_id = r.role_id";
$stmt = $db->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/users.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" />
</head>

<body>
    <header>
        <h1>Liste des utilisateurs</h1>
    </header>

    <div class="container">
        <table>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['user_nom']) ?></td>
                    <td><?= htmlspecialchars($user['user_prenom']) ?></td>
                    <td><?= htmlspecialchars($user['user_tel']) ?></td>
                    <td><?= htmlspecialchars($user['user_mail']) ?></td>
                    <td><?= htmlspecialchars($user['role_name']) ?></td>
                    <td>
                        <a href="form.php?id=<?= $user['user_id'] ?>" title="Éditer"><i class="fas fa-edit"></i></a>
                        <a href="delete.php?id=<?= $user['user_id'] ?>"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')"
                            title="Supprimer"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>