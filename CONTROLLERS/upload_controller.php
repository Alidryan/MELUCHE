<?php

include_once 'MODELS/includes/constants.php';

$maxsize = MAX_SIZE;
$token_upload = Token::generer('upload');

$errmsg = "";
$showPage = true;
if(isset($_GET['erreur']) && !empty($_GET['erreur'])) {
	
	$erreur = $_GET['erreur'];
		if ($erreur == "notlogged")
			$errmsg = "Vous devez être connecté pour poster une image ! <a href='login.php'>Se connecter.</a>";
		else if ($erreur == "captcha")
			$errmsg = "Captcha invalide ! Veuillez réessayer.";
		else if ($erreur == "size")
			$errmsg = "Image trop lourde ou poids inconnu !";
		else if ($erreur == "format")
			$errmsg = "L'image doit être en format PNG, JPG, JPEG ou GIF !";
		else if ($erreur == "titre")
			$errmsg = "Titre trop long !";
		else if ($erreur == "existe")
			$errmsg = "Une image avec la même url existe déja!";
		else if ($erreur == "notimage")
			$errmsg = "Le lien ne renvoit pas vers une image!";
		else if ($erreur == "banned")
			$errmsg = "Vous avez été banni";
		else if ($erreur == "tags")
			$errmsg = "Erreur de tags!";
		else if ($erreur == "url")
			$errmsg = "URL trop longue !";
		else if ($erreur == "pseudo")
			$errmsg = "Vous devez choisir un pseudo pour poster une image ! <a href='pseudo.php'>Choisir un pseudo</a>";
		else
			$errmsg = "Veuillez réessayer";

} else if (!isset($_SESSION['id'])) {
	$errmsg = "Vous devez être connecté pour pouvoir poster une image ! <a href='login.php'>Se connecter</a>.";
	$showPage = false;
} else if(!isset($_SESSION['pseudo'])) {
	$errmsg = "Vous devez choisir un pseudo pour poster une image ! <a href='pseudo.php'>Choisir un pseudo</a>";
	$showPage = false;
}

