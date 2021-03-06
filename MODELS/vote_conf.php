<?php
include ("includes/identifiants.php");
include_once ('includes/token.class.php');
include_once ("includes/constants.php");

function pointsTotauxUpdate($id_image) {

	global $bdd;	


	$req = $bdd->prepare ('SELECT * FROM images WHERE id = :id_image');
	$req->execute ([
		':id_image' => $id_image,
	]);
	$resultat = $req->fetch ();

	$nb_vote_positif = $resultat["nb_vote_positif"];
	$nb_vote_negatif = $resultat["nb_vote_negatif"];
	$pointsTotaux = $nb_vote_positif - $nb_vote_negatif;

	$req = $bdd->prepare ('UPDATE images SET pointsTotaux = :pointsTotaux WHERE id = :id_image');
	$req->execute ([
		':id_image' => $id_image,
		':pointsTotaux' => $pointsTotaux,
	]);
}
/*
if (!Token::verifier (3600, 'vote')) { // Penser à creer $token
	exit();
}

if (empty($_POST['id_image']) OR empty($_POST['vote']) OR !is_int($_POST['id_image']) OR !is_int($_POST['vote'])) {
	echo  'lol';
	exit();
}
A corriger/A faire
 */
if (!isset($_SESSION)) {
	session_start ();
}
if (!$_SESSION) {
	header("HTTP/1.0 403 Forbidden");
	exit();
}
/*
$referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
$domaine= parse_url(SITE_DOMAINE, PHP_URL_HOST);
if ($referer != $domaine) {
	header("HTTP/1.0 403 Forbidden");
	exit();
}
*/
$id_user = $_SESSION['id'];
if (!$id_user) {
	header("HTTP/1.0 403 Forbidden");
	exit();
}

$req = $bdd->prepare ('SELECT id FROM images WHERE nom_hash = :idhash');
$req->execute ([
	':idhash' => $_POST['idhash'],
]);
$resultat = $req->fetch ();

if (!$resultat) {
	header("HTTP/1.0 400 Bad Request");
	exit();
}
$id = $resultat['id'];

$req = $bdd->prepare ('SELECT id FROM ban WHERE id_user = :id_user');
$req->execute ([
    'id_user' => $id_user,
]);
$resultat = $req->fetch ();

if($resultat) {
	header("HTTP/1.0 403 Forbidden");
	exit();
}

$req = $bdd->prepare ('SELECT * FROM vote WHERE id_image = :id_image AND id_user = :id_user');
$req->execute ([
	':id_image' => $id,
	':id_user' => $id_user,
]);

$resultat = $req->fetch ();
$ancien_vote = $resultat["vote"];

if (!isset($resultat) OR $resultat == null) // Si User n'a pas encore voté sur cette image
{
	$req = $bdd->prepare ('INSERT INTO vote(id_user, id_image, vote) VALUES(:id_user, :id_image, :vote)');
	$req->execute ([
		':id_user' => $id_user,
		':id_image' => $id,
		':vote' => $_POST['vote'], //-1 pour vote negatif, 1 pour vote positif
	]);
}
if(!$resultat OR $resultat == null OR $ancien_vote == '0') {
	if ($_POST['vote'] == -1) {
		$req = $bdd->prepare ('UPDATE images SET nb_vote_negatif = nb_vote_negatif + 1  WHERE id = :id_image');
		$req->execute ([
			':id_image' => $id,
		]);
		pointsTotauxUpdate($id);
	} elseif ($_POST['vote'] == 1) {
		$req = $bdd->prepare ('UPDATE images SET nb_vote_positif = nb_vote_positif + 1  WHERE id = :id_image');
		$req->execute ([
			':id_image' => $id,
		]);
		pointsTotauxUpdate($id);
	}

	if($ancien_vote == '0') {

		$req = $bdd->prepare ('UPDATE vote SET vote = :vote  WHERE id_image = :id_image AND id_user = :id_user ');
		$req->execute ([
			':id_user' => $id_user,
			':id_image' => $id,
			':vote' => $_POST['vote'], //-1 pour vote negatif, 1 pour vote positif, 0 pour nul
		]);


	}

} else { // Si user a déja voté sur cette image

	$req = $bdd->prepare ('UPDATE vote SET vote = :vote  WHERE id_image = :id_image AND id_user = :id_user ');
	$req->execute ([
		':id_user' => $id_user,
		':id_image' => $id,
		':vote' => $_POST['vote'], //-1 pour vote negatif, 1 pour vote positif, 0 pour nul
	]);

	if ($_POST['vote'] == -1) {
		if ($ancien_vote == -1) {
			exit();
		} elseif ($ancien_vote == 1) {
			$req = $bdd->prepare ('UPDATE images SET nb_vote_negatif = nb_vote_negatif + 1,nb_vote_positif = nb_vote_positif - 1 WHERE id = :id_image');
			$req->execute ([
				':id_image' => $id,
			]);
			pointsTotauxUpdate($id);
		}
	} else if ($_POST['vote'] == 1) {
		if ($ancien_vote == 1) {
			exit();
		} elseif ($ancien_vote == -1) {

			$req = $bdd->prepare ('UPDATE images SET nb_vote_positif = nb_vote_positif + 1,nb_vote_negatif = nb_vote_negatif - 1  WHERE id = :id_image');
			$req->execute ([
				':id_image' => $id,
			]);
			pointsTotauxUpdate($id);
		}
	} else if($_POST['vote'] == 0){
		if ($ancien_vote == 1) {
			$req = $bdd->prepare ('UPDATE images SET nb_vote_positif = nb_vote_positif - 1 WHERE id = :id_image');
			$req->execute ([
				':id_image' => $id,
			]);
			pointsTotauxUpdate($id);
		} else if ($ancien_vote == -1) {

			$req = $bdd->prepare ('UPDATE images SET nb_vote_negatif = nb_vote_negatif - 1  WHERE id = :id_image');
			$req->execute ([
				':id_image' => $id,
			]);
			pointsTotauxUpdate($id);
		}




	}
}
