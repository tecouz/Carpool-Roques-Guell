<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

// Vérifier si l'ID de l'utilisateur est fourni
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Supprimer l'image de profil de l'utilisateur s'il en a une
    $sql = "SELECT user_image FROM table_user WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && !empty($result['user_image'])) {
        $image_path = $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $result['user_image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Supprimer les préférences de l'utilisateur
    $sql = "DELETE FROM table_user_preference WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Supprimer l'utilisateur
    $sql = "DELETE FROM table_user WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Rediriger vers la page d'accueil ou afficher un message de succès
    header("Location: index.php");
    exit();
} else {
    // Afficher un message d'erreur si l'ID de l'utilisateur n'est pas fourni
    echo "Erreur : ID de l'utilisateur non spécifié.";
}
?>