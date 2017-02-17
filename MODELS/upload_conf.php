<?php

include_once ("includes/constants.php");

/*
ERREURS RETOURNEES:

notlogged
captcha
size
format
titre

*/
include ("includes/identifiants.php");
include_once ('includes/securite.class.php');

$img = $_FILES['file'];

if (!isset($_SESSION)) {
    session_start ();
}
$id_user = $_SESSION['id'];
if (!$id_user) header ('Location:../upload.php?erreur=notlogged');

$captcha = $_POST['g-recaptcha-response'];
if (!$captcha) {
    header ('Location:../upload.php?erreur=captcha');
    exit();
}
// Verification de la validité du captcha
$response = file_get_contents ("https://www.google.com/recaptcha/api/siteverify?secret=6LefaBUUAAAAAOCU1GRih8AW-4pMJkiRRKHBmPiE&response=" . $captcha);
$decoded_response = json_decode ($response);
if ($decoded_response->success == false) {
    header ('Location:../upload.php?erreur=captcha');
    exit();
}

if ($img['size'] > MAX_SIZE) {
    header ('Location:../upload.php?erreur=size');
    exit();
}

$extensions_valides = array('jpg', 'jpeg', 'gif', 'png');
$extension_image = strtolower (substr (strrchr ($img['name'], '.'), 1));
if (!in_array ($extension_image, $extensions_valides)) {
    header ('Location:../upload.php?erreur=format');
    exit();
}

$titre = $_POST['titre'];
if (strlen($titre) > 255 || strlen($titre) == 0) {
    header ('Location:../upload.php?erreur=titre');
    exit();
}

$req = $bdd->prepare ('INSERT INTO images(titre, id_user, nom_original, format, date_creation) VALUES(:titre, :id_user, :nom_original, :format, NOW())');
$req->execute ([
    ':titre' => htmlspecialchars ($_POST['titre']),
    ':id_user' => $id_user,
    ':nom_original' => htmlspecialchars ($img['name']), // A FAIRE ! : Verifier que < 255
    ':format' => $extension_image,
]);

$idbase = $bdd->lastInsertId ();
$id = sha1 ($idbase . SALT_ID);

$req = $bdd->prepare ('UPDATE images SET nom_hash = :nom_hash  WHERE id = :id ');
$req->execute ([
    'nom_hash' => $id,
    'id' => $idbase,
]);

$direction = '/../images/' . $id . "." . $extension_image;
move_uploaded_file ($img['tmp_name'],__DIR__ . $direction);

$imagebase = __DIR__ . $direction;
list($width, $height) = getimagesize ($imagebase);

if (($extension_image == "jpg") OR ($extension_image == "jpeg")) {
    $source = imagecreatefromjpeg ($imagebase);
} elseif ($extension_image == "png") {
    $source = imagecreatefrompng ($imagebase);
} elseif ($extension_image == "gif") {
    $source = imagecreatefromgif ($imagebase);
}

if ($width >= $height)
{
    $ratio = $width/VIGNETTE_WIDTH;
} else {
    $ratio = $height/VIGNETTE_HEIGHT;
}
$newwidth = $width/$ratio;
$newheight = $height/$ratio;
$thumb = imagecreatetruecolor ($newwidth, $newheight);
imagecopyresized ($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

if (($extension_image == "jpg") OR ($extension_image == "jpeg")) {
    imagejpeg ($thumb, __DIR__ . '/vignettes/' . $id . '.jpg');
} elseif ($extension_image == "png") {
    imagepng ($thumb, __DIR__ . '/vignettes/' . $id . '.png');
} elseif ($extension_image == "gif") {
    imagegif ($thumb, __DIR__ . '/vignettes/' . $id . '.gif');
}

header ('Location:../view.php?id=' . $id);
?>