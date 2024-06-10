<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

function generateFileName($str, $ext)
{
    $result = $str;
    $result = strtolower($result);
    $pattern = array(' ', 'é', 'è', 'ë', 'ê', 'á', 'à', 'ä', 'â', 'å', 'ã', 'ó', 'ò', 'ö', 'ô', 'õ', 'í', 'ì', 'ï', 'ú', 'ù', 'ü', 'û', 'ý', 'ÿ', 'ø', 'œ', 'ç', 'ñ', 'ß', 'ț', 'ș', 'ř', 'ž', 'á', 'č', 'ď', 'é', 'ě', 'í', 'ň', 'ó', 'ř', 'š', 'ť', 'ú', 'ů', 'ý', 'ž');
    $replace = array('-', 'e', 'e', 'e', 'e', 'a', 'a', 'a', 'a', 'a', 'a', 'o', 'o', 'o', 'o', 'o', 'i', 'i', 'i', 'u', 'u', 'u', 'u', 'y', 'y', 'o', 'ae', 'c', 'n', 'ss', 't', 's', 'r', 'z', 'a', 'c', 'd', 'e', 'e', 'i', 'n', 'o', 'r', 's', 't', 'u', 'u', 'y', 'z');
    $result = str_replace($pattern, $replace, $result);

    return $result;
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if ($user_id > 0) {
    // Code pour mettre à jour les informations de l'utilisateur
} else {
    // Code pour insérer un nouvel utilisateur
    $stmt->execute();
    $user_id = $db->lastInsertId();
}

// Supprimer les préférences existantes de l'utilisateur
$sql = "DELETE FROM table_user_preference WHERE user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// Insérer les nouvelles préférences de l'utilisateur
if (isset($_POST['preferences']) && $user_id > 0) {
    $sql = "INSERT INTO table_user_preference (user_id, preference_id) VALUES (:user_id, :preference_id)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    foreach ($_POST['preferences'] as $preference_id) {
        $stmt->bindValue(':preference_id', $preference_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if ($user_id > 0) {
    $sql = "UPDATE table_user
SET user_nom = :user_nom, user_prenom = :user_prenom, user_tel = :user_tel, user_mail = :user_mail
WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_nom', $_POST['user_nom'], PDO::PARAM_STR);
    $stmt->bindValue(':user_prenom', $_POST['user_prenom'], PDO::PARAM_STR);
    $stmt->bindValue(':user_tel', $_POST['user_tel'], PDO::PARAM_STR);
    $stmt->bindValue(':user_mail', $_POST['user_mail'], PDO::PARAM_STR);
    $stmt->execute();
} else {
    $sql = "INSERT INTO table_user (user_nom, user_prenom, user_tel, user_mail)
VALUES (:user_nom, :user_prenom, :user_tel, :user_mail)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_nom', $_POST['user_nom'], PDO::PARAM_STR);
    $stmt->bindValue(':user_prenom', $_POST['user_prenom'], PDO::PARAM_STR);
    $stmt->bindValue(':user_tel', $_POST['user_tel'], PDO::PARAM_STR);
    $stmt->bindValue(':user_mail', $_POST['user_mail'], PDO::PARAM_STR);
    $stmt->execute();
    $user_id = $db->lastInsertId();
}

if (isset($_FILES['user_image']) && $_FILES['user_image']['name'] != "") {
    $sql = "SELECT user_image FROM table_user WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['user_image'] != "" && file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/" . $row['user_image'])) {
            unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/" . $row['user_image']);
        }
    }

    $extension = pathinfo($_FILES['user_image']['name'], PATHINFO_EXTENSION);
    $filename = generateFileName($_POST['user_nom'] . "_" . $_POST['user_prenom'], $extension);
    move_uploaded_file($_FILES['user_image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $filename . "." .
        $extension);

    $sql = "UPDATE table_user SET user_image = :user_image WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_image', $filename . "." . $extension, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
}

header("Location: index.php");
?>