<?php
require_once 'includes/vendor/autoload.php';

$id_token = $_POST["idtoken"];

$client = new Google_Client(['client_id' => $CLIENT_ID]);
$payload = $client->verifyIdToken($id_token);
if ($payload) {
	$userid = $payload['sub'];
	
	//vérifier si userid dans bdd, sinon l'ajouter

} else {
	//Token invalide
}


?>