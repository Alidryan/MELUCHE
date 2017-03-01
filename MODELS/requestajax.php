<?php
include("includes/identifiants.php");
include_once("includes/constants.php");
include("cardsinfo.php");
include("check_grade.php");
ini_set('display_errors', 1);
if (empty($_POST['sort']) || !is_numeric($_POST['startIndex']) || !is_numeric($_POST['size'])) {
    exit();
}
$sort = htmlspecialchars($_POST['sort']);
$startIndex = intval($_POST['startIndex']);
$size = intval($_POST['size']);
if(!empty($_POST['search']))
	$search = "%".$_POST['search']."%";
else
	$search = "%";
$json = array();


if ($sort == "hot") {

    $req = $bdd->prepare ("SELECT nom_hash FROM images WHERE (supprime=0 AND (titre LIKE :search OR tags LIKE :search)) ORDER BY pointsTotaux DESC LIMIT :startIndex , :size" );
	$req->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
	$req->bindParam(':size', $size, PDO::PARAM_INT);
	$req->bindParam(':search', $search, PDO::PARAM_STR);
	$req->execute();

    while ($resultat = $req->fetch()) {
        array_push($json, json_decode(getInfo($resultat["nom_hash"])));
    }
	echo json_encode($json);

} elseif ($sort == "new") {
    $req = $bdd->prepare ("SELECT nom_hash FROM images WHERE (supprime=0 AND (titre LIKE :search OR tags LIKE :search)) ORDER BY date_creation DESC LIMIT :startIndex , :size" );
	
	$req->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
	$req->bindParam(':size', $size, PDO::PARAM_INT);
	$req->bindParam(':search', $search, PDO::PARAM_STR);
	$req->execute();


    

    while ($resultat = $req->fetch()) {
		array_push($json, json_decode(getInfo($resultat["nom_hash"])));
    }
	echo json_encode($json);

} elseif ($sort == "random") {

    $req = $bdd->prepare ("SELECT nom_hash FROM images WHERE (supprime=0 AND (titre LIKE :search OR tags LIKE :search)) ORDER BY RAND() DESC LIMIT :size" );
	
	$req->bindParam(':size', $size, PDO::PARAM_INT);
	$req->bindParam(':search', $search, PDO::PARAM_STR);
	$req->execute();



    while ($resultat = $req->fetch()) {
		array_push($json, json_decode(getInfo($resultat["nom_hash"])));
	}
	echo json_encode($json);
} elseif ($sort == "report" && $grade > 0) {
    $req = $bdd->prepare ("SELECT images.nom_hash, count(*) FROM images inner join report on images.id = report.id_image WHERE (supprime=0 AND (titre LIKE :search OR tags LIKE :search)) GROUP BY images.id ORDER BY count(*) DESC LIMIT :startIndex , :size");
	
	
	
	$req->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
	$req->bindParam(':size', $size, PDO::PARAM_INT);
	$req->bindParam(':search', $search, PDO::PARAM_STR);
	$req->execute();
    

    while ($resultat = $req->fetch()) {
		array_push($json, json_decode(getInfo($resultat["nom_hash"])));
	}
	echo json_encode($json);
} elseif($sort == "deleted" && $grade > 0) {
	$req = $bdd->prepare ("SELECT nom_hash FROM images WHERE (supprime=1 AND (titre LIKE :search OR tags LIKE :search)) ORDER BY date_creation DESC LIMIT :startIndex , :size" );
	
	$req->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
	$req->bindParam(':size', $size, PDO::PARAM_INT);
	$req->bindParam(':search', $search, PDO::PARAM_STR);
	$req->execute();
    

    while ($resultat = $req->fetch()) {
		array_push($json, json_decode(getInfo($resultat["nom_hash"])));
    }
	echo json_encode($json);


}
?>
