<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

// Nombre total de trajets
$stmt = $db->prepare("SELECT COUNT(*) FROM table_trajet");
$stmt->execute();
$total_trajets = $stmt->fetchColumn();

// Nombre de pages
$nb_pages = ceil($total_trajets / 20);

// Page courante
$page_courante = isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nb_pages ? $_GET['page'] : 1;

// Calcul de l'offset pour la requête SQL
$offset = ($page_courante - 1) * 20;

// Requête SQL pour récupérer les trajets avec les noms des centres et des utilisateurs
$sql = "SELECT t.trajet_id, t.trajet_depart, c.centre_nom AS trajet_arriver, t.trajet_voiture, CONCAT(u.user_prenom, ' ', u.user_nom) AS user_fullname
        FROM table_trajet t
        JOIN table_centre c ON t.centre_id = c.centre_id
        JOIN table_user u ON t.user_id = u.user_id
        LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
$stmt->bindValue(':limit', 20, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HMTL -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
    <link rel="stylesheet" type="text/css" href="../../css/covoit.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" />
</head>

<body>
    <header>
        <div class="login">
            <p>
                <a href="../profile/index.php">profile</a>
            </p>
        </div>
        <h1>Bienvenue
            <?= htmlspecialchars($_SESSION['user_name'] ?? ''); ?>
        </h1>
    </header>

    <div class="trajets">
        <a href="form.php" class="btn-ajouter">Ajouter un trajet</a>

        <?php if (!empty($result)) { ?>
        <table>
            <tr>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Voiture</th>
                <th>Utilisateur</th>
                <th>Edit / Supp.</th>
            </tr>
            <?php foreach ($result as $row) { ?>
            <tr>
                <td><?= htmlspecialchars($row["trajet_depart"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["trajet_arriver"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["trajet_voiture"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["user_fullname"] ?? '') ?></td>
                <td>
                    <a href="form.php?id=<?= $row["trajet_id"] ?>" title="Éditer"><i class="fas fa-edit"></i></a>
                    <a href="#" onclick="confirmDelete(<?= $row["trajet_id"] ?>)" title="Supprimer"><i
                            class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
            <?php } ?>
        </table>
        <?php } else { ?>
        <p>Aucun trajet trouvé.</p>
        <?php } ?>

        <div class="pagination">
            <?php for ($i = 1; $i <= $nb_pages; $i++) { ?>
            <a href="?page=<?= $i ?>"><?= $i ?></a>
            <?php } ?>
        </div>
    </div>

    <script>
    function confirmDelete(trajetId) {
        if (confirm("Êtes-vous sûr de vouloir supprimer ce trajet ?")) {
            window.location.href = "delete.php?id=" + trajetId;
        }
    }
    </script>
</body>

</html>