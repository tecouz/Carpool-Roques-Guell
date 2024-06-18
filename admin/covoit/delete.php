<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

// Vérifier si l'identifiant du trajet est fourni
if (isset($_GET['id'])) {
    $trajet_id = $_GET['id'];

    // Supprimer le trajet de la base de données
    $sql = "DELETE FROM table_trajet WHERE trajet_id = :trajet_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':trajet_id', $trajet_id, PDO::PARAM_INT);
    $stmt->execute();

    // Rediriger vers la page d'accueil après la suppression
    header("Location: index.php");
    exit();
} else {
    // Rediriger vers la page d'accueil si aucun identifiant n'est fourni
    header("Location: index.php");
    exit();
}
?>