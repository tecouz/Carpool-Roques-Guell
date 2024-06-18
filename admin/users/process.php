<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

// Vérifier si les données du formulaire ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $user_id = $_POST['user_id'];
    $user_nom = $_POST['user_nom'];
    $user_prenom = $_POST['user_prenom'];
    $user_tel = $_POST['user_tel'];
    $user_mail = $_POST['user_mail'];
    $role_id = $_POST['role_id'];

    // Mettre à jour les informations de l'utilisateur dans la table table_user
    $sql = "UPDATE table_user SET user_nom = :user_nom, user_prenom = :user_prenom, user_tel = :user_tel, user_mail = :user_mail WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_nom', $user_nom, PDO::PARAM_STR);
    $stmt->bindParam(':user_prenom', $user_prenom, PDO::PARAM_STR);
    $stmt->bindParam(':user_tel', $user_tel, PDO::PARAM_STR);
    $stmt->bindParam(':user_mail', $user_mail, PDO::PARAM_STR);
    $stmt->execute();

    // Vérifier si un rôle a été sélectionné
    if (!empty($role_id)) {
        // Supprimer l'ancien rôle de l'utilisateur
        $sql_delete = "DELETE FROM table_role_user WHERE user_id = :user_id";
        $stmt_delete = $db->prepare($sql_delete);
        $stmt_delete->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_delete->execute();

        // Insérer le nouveau rôle pour l'utilisateur
        $sql_insert = "INSERT INTO table_role_user (user_id, role_id) VALUES (:user_id, :role_id)";
        $stmt_insert = $db->prepare($sql_insert);
        $stmt_insert->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_insert->bindParam(':role_id', $role_id, PDO::PARAM_INT);
        $stmt_insert->execute();
    }
    // Rediriger vers la liste des utilisateurs après la mise à jour
    header("Location: index.php");
    exit();
} else {
    // Rediriger vers la liste des utilisateurs si aucune donnée n'a été soumise
    header("Location: index.php");
    exit();
}
?>