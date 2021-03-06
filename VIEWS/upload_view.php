<!DOCTYPE html>
<html lang="fr">
	<?php echo $HEAD ?>
	<body>
		<?php echo $NAVBAR ?>
		<div class="container" id="main_page">

			<?php if(!empty($errmsg)): ?>
			<div class='alert alert-danger erreur'>
				<a href="#" class="close" data-dismiss="alert" aria-label="fermer">×</a>
				<?php echo $errmsg ?>
			</div>
			<?php endif ?>
			<?php if($mode_maintenance): ?>
			<div class="form-group col-xs-6">
				<br></br> <br></br>
				<p>Vous ne pouvez pas ajouter d'images !</p>
				<p>En effet, il est interdit de publier du contenu vis à vis de la campagne électorale après Vendredi 21 Avril 2017 à 23H59. (Heure de Paris)</p>
				<p>Nous voulons respecter cette règle, c'est pourquoi aucun contenu ne sera publié sur Melenshack après Vendredi 21 Avril 2017 à 23H59 jusqu'à la fermeture des derniers bureaux de vote.(Dimanche 23 Avril 2017 à 20H00) </p>
			</div>
			<?php endif ?>
			<?php if($showPage): ?>

			<form class="upload" action="MODELS/upload_conf.php" autocomplete="off" method="post" enctype="multipart/form-data">
				<?php if(!$change): ?>
				<h1>Ajouter une image</h1>
				<div class="sub">Pour ajouter plusieurs images, <a href="upload_masse.php">cliquez ici</a></div>
				<?php else: ?>
				<h1>Modifier une image</h1>
				<?php endif ?>
				<div class="form-group col-xs-5">
					<?php if(!$change): ?>
					<label for="titre">Titre de l'image (optionnel):</label>
					<input type="text" class="form-control input-lg" name="titre" id="titre" placeholder="Titre de votre post" autofocus>

					<?php endif ?>
					<p id="formats"><small>Formats acceptés: JPG, PNG, GIF. Poids max: <?php echo $maxsize/1000000 ?> Mo</small></p>
					<label for="file" id="drop">
						<div>
							<p>
							<label for="file" id="filelabel">	
								<strong><span class="glyphicon glyphicon-folder-open"></span>Choisissez une image</strong>													<input id="file" name="file" type="file" style="display: none;">
							</label> ou glissez la ici</p>

							<div id="nameContainer" hidden>
								<p><span class="glyphicon glyphicon-ok"></span><span id="name"></span></p>
							</div>
							<div id="errorContainer" hidden>
								<p><span class="glyphicon glyphicon-remove"></span><span id="error"></span></p>
							</div>
						</div>
					</label>

					<div id="urlgroup" class="form-group form-inline">
						<label for="url" id="urltext">ou entrez l'URL de l'image:</label>
						<input type="url" id="url" name="url" class="form-control"/>
					</div>
					<?php if(!$change): ?>
					<div class="tags">
						<label for="tagsinput"><span class="glyphicon glyphicon-tags"></span>Tags (séparés par des virgules) (optionnel):</label>
						<br>
						<select multiple name="tags[]" id="tagsinput" type="text" data-role="tagsinput" >
	<?php if($tag): ?>
	<option selected value='<?php echo $tag ?>'><?php echo $tag ?></option>
	<?php endif ?>
	</select>
					</div>
					<?php if($showPseudo): ?>
						<br><label for="pseudo">Pseudo du créateur (optionnel): </label>
						<input type="text" id="pseudo" name="pseudo" class="form-control"/>

					<?php endif ?>
					<!--<div class="g-recaptcha" data-sitekey="6LeKlhgUAAAAAAaxaZrJdqgzv57fCkNmX5UcXrwG" data-callback="recaptchaCallback"></div>
					--><br>
					<?php else: ?>
					<input name="idhash" value='<?php echo $idhash ?>' hidden>
					<?php endif ?>
					<input type="hidden" name="token" id="token" value="<?php echo $token_upload?>">
					<input type="submit" id="submit" class="btn btn-primary btn-lg" name="submit" value="Poster l'image" accept="image/*" required>
				</div>
				<input type="hidden" id="max" name="taille_max" value=<?php echo "'$maxsize'" ?> />
			<small>Merci de faire attention à la provenance de vos images ! Préférez les images libres de droit, issues du site officiel ou créées par vous.</small>
			</form>
			<?php endif ?>
		</div>
	</body>
</html>
