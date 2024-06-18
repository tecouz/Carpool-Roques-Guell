<?php
ob_start(); // Démarrer la capture des sorties

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Vérifier si un ID de trajet est fourni pour l'édition
$edit_mode = false;
if (isset($_POST['trajet_id'])) {
    $edit_mode = true;
    $trajet_id = $_POST['trajet_id'];
}

if ($edit_mode) {
    // Code pour mettre à jour les informations du trajet
    $sql = "UPDATE table_trajet
            SET trajet_depart = :trajet_depart,
                trajet_arriver = :trajet_arriver,
                centre_id = :centre_id,
                trajet_voiture = :trajet_voiture,
                user_id = :user_id
            WHERE trajet_id = :trajet_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':trajet_id', $trajet_id, PDO::PARAM_INT);
} else {
    // Code pour insérer un nouveau trajet
    $sql = "INSERT INTO table_trajet (trajet_depart, trajet_arriver, centre_id, trajet_voiture, user_id)
            VALUES (:trajet_depart, :trajet_arriver, :centre_id, :trajet_voiture, :user_id)";
    $stmt = $db->prepare($sql);
}

$stmt->bindValue(':trajet_depart', $_POST['trajet_depart'], PDO::PARAM_STR);
$stmt->bindValue(':trajet_arriver', $_POST['trajet_arriver'], PDO::PARAM_INT);
$stmt->bindValue(':centre_id', $_POST['trajet_arriver'], PDO::PARAM_INT);
$stmt->bindValue(':trajet_voiture', $_POST['trajet_voiture'], PDO::PARAM_STR);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

$stmt->execute();

if (!$edit_mode) {
    $trajet_id = $db->lastInsertId();
}

// Rediriger vers la page d'affichage des trajets
header("Location: index.php");
ob_end_flush(); // Envoyer les sorties capturées et terminer la capture
exit();
?>