<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

// Récupérer la liste des centres
$stmt_centres = $db->query("SELECT centre_id, centre_nom FROM table_centre");
$centres = $stmt_centres->fetchAll(PDO::FETCH_ASSOC);

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Vérifier si un ID de trajet est fourni pour l'édition
$edit_mode = false;
if (isset($_GET['id'])) {
    $edit_mode = true;
    $trajet_id = $_GET['id'];
    // Récupérer les détails du trajet à partir de la base de données
    $stmt = $db->prepare("SELECT * FROM table_trajet WHERE trajet_id = ?");
    $stmt->execute([$trajet_id]);
    $trajet = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $edit_mode ? 'Éditer un trajet' : 'Ajouter un trajet' ?></title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
    <h1><?= $edit_mode ? 'Éditer un trajet' : 'Ajouter un trajet' ?></h1>
    <form method="post" action="process.php">
        <?php if ($edit_mode) { ?>
        <input type="hidden" name="trajet_id" value="<?= $trajet['trajet_id'] ?>">
        <?php } ?>
        <label for="trajet_depart">Départ :</label>
        <input type="text" name="trajet_depart" id="trajet_depart"
            value="<?= $edit_mode ? $trajet['trajet_depart'] : '' ?>" required>

        <label for="trajet_arriver">Arrivée :</label>
        <select name="trajet_arriver" id="trajet_arriver" required>
            <option value="">Sélectionnez une destination</option>
            <?php foreach ($centres as $centre) { ?>
            <option value="<?= $centre['centre_id'] ?>"
                <?= $edit_mode && $trajet['centre_id'] == $centre['centre_id'] ? 'selected' : '' ?>>
                <?= $centre['centre_nom'] ?>
            </option>
            <?php } ?>
        </select>

        <label for="trajet_voiture">Voiture :</label>
        <input type="text" name="trajet_voiture" id="trajet_voiture"
            value="<?= $edit_mode ? $trajet['trajet_voiture'] : '' ?>" required>

        <input type="hidden" name="user_id" value="<?= $user_id ?>">

        <button type="submit"><?= $edit_mode ? 'Enregistrer' : 'Ajouter' ?></button>
    </form>
</body>

</html>