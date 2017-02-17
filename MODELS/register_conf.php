<?php

/*
ERREURS RENVOYEES :

captcha
doublon
pass
email
loginmdp
token



*/
include("includes/identifiants.php");
include_once('includes/securite.class.php');
include_once('includes/token.class.php');
include_once ("includes/constants.php");

if(Token::verifier(600, 'inscription')) 
{
  if(!empty($_POST['g-recaptcha-response']) && !empty($_POST['pseudo']) AND !empty($_POST['pass']) AND !empty($_POST['confpass']) AND !empty($_POST['email']))
  {
  	$captcha = $_POST['g-recaptcha-response'];
	if (!$captcha) {
    	header ('Location:../register.php?erreur=captcha');
    	exit();
	}
	
	// Verification de la validité du captcha
	$response = file_get_contents ("https://www.google.com/recaptcha/api/siteverify?secret=6LefaBUUAAAAAOCU1GRih8AW-4pMJkiRRKHBmPiE&response=" . $captcha);
	$decoded_response = json_decode ($response);
	if ($decoded_response->success == false) {
    	header ('Location:../register.php?erreur=captcha');
    	exit();
	}


	$email = $_POST['email'];
	$pseudo = $_POST['pseudo'];
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) 
	{
		$pass = Securite::bdd($_POST['pass']);
		$confpass = Securite::bdd($_POST['confpass']);

		$pass_hache = hash('sha256',SALT_PASS. $pass); // !! changer le salt pour le site !!
	    $confpass_hache = hash('sha256',SALT_PASS . $confpass); // !! changer le salt pour le site !!

		if ($pass_hache == $confpass_hache) 
		{
			$req = $bdd->prepare('SELECT id FROM users WHERE pseudo = :pseudo OR email = :email');
			$req->execute([
				'pseudo' => $pseudo,
				'email' => $email,
			]);
				
			$resultat = $req->fetch();
			if (!$resultat)
			{
			 // Insertion du message à l'aide d'une requête préparée
			 $req = $bdd->prepare('INSERT INTO users(pseudo, pass, email, dateinscription) VALUES(:pseudo, :pass, :email, NOW())');
					$req->execute([
								   ':pseudo' => htmlspecialchars($_POST['pseudo']),
								   ':pass' => htmlspecialchars($pass_hache),
								   ':email' => htmlspecialchars($email)
								   ]);
								   
						echo 'SUCCESS'; // Inscription réussi !
						
						$req = $bdd->prepare('SELECT id FROM users WHERE pseudo = :pseudo OR email = :email');
								$req->execute([
									'pseudo' => $pseudo,
									'email' => $email,
								]);
									
						$resultat = $req->fetch();
						if(!isset($_SESSION)){
						  session_start();
						}
						$_SESSION['id'] = $resultat['id'];
						$_SESSION['pseudo'] = $pseudo;
						
						header('Location:../index.php');
			}
			else 
			{
			   // Doublon Pseudo ou email
			  header('Location:../register.php?erreur=doublon');
			}
		}
		else 
		{
		  // Mauvais Mot de passe 
		  header('Location:../register.php?erreur=pass');
		}
	}
	else 
	{
	   // Mauvais email
	   header('Location:../register.php?erreur=email');
	}
  } 
  else 
  {
 // Mauvais Mot de passe ou Login
	header('Location:../register.php?erreur=loginmdp');
  }
}
else 
{
    //Mauvais Token
	header('Location:../register.php?erreur=token');
}
?>