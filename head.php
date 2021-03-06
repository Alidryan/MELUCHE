<head>

	
	<meta property="og:url"                content=<?php echo "'$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]'" ?> >
	<meta property="og:locale"              content="fr_FR" >
	<meta property="og:description"        content="Mélenshack, la banque d'images de la France Insoumise !" >
	<meta property="fb:app_id"              content="1849815745277262" >

	<?php if(!isset($urlSource)): ?>
	<meta property="og:title"              content=<?php if(!empty($titre)) echo '"'.htmlspecialchars($titre).' | Melenshack'.'"';
													else echo "Mélenshack" ?> >

	<meta property="og:type"               content="website" >
	<meta property="og:image"              content=<?php echo "'$protocol$_SERVER[HTTP_HOST]"."/assets/melenshack.png'" ?> >
	<meta property="og:image:width"              content="1600" >
	<meta property="og:image:height"              content="480" >

	<?php elseif(!$supprime): ?>
	<meta name="twitter:card" content="summary_large_image">
	<meta property="og:type"               content="article" >
	<meta property="og:title"              content=<?php if(!empty($titre)) echo '"'.htmlspecialchars($titre).' | Melenshack'.'"';
															else echo "Mélenshack" ?> >
		<?php if(strpos($urlSource, 'http') !== false): ?>
			<meta property="og:image"              content=<?php  if(isset($urlSource)) echo "'$urlSource'" ?> >
		<?php else: ?>
			<meta property="og:image"              content=<?php echo "'$protocol$_SERVER[HTTP_HOST]"."$urlSource'" ?> >
		<?php endif ?>
	<meta property="og:image:width"              content=<?php  if(isset($width)) echo "'$width'" ?> >
	<meta property="og:image:height"              content=<?php  if(isset($height)) echo "'$height'" ?> >
	<?php endif ?>

	<meta charset="utf-8">
	<meta name="description" content="La banque d'images de la France Insoumise et de son candidat, Jean-Luc Mélenchon">
	<title><?php if(!empty($titre)) echo htmlspecialchars($titre). ' | Melenshack'; else echo "Mélenshack" ?></title>
	<link rel="icon" type="image/png" href="/assets/melenshack_small.png">
	<link href="https://fonts.googleapis.com/css?family=Roboto:700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet">
	<link rel="stylesheet" href="/css/bootstrap.css">
	<link rel="stylesheet" href="/css/style.css?v1.04">
	<link rel="stylesheet" href="/css/bootstrap-tagsinput.css"/>

	<script src="/libs/jquery.min.js"></script>
	<script src="/libs/jquery-ui.min.js"></script><!-- ATTENTION : JQUERY UI AVANT BOOTSTRAP SINON PB TOOLTIP -->
	<script src="/libs/bootstrap.min.js"></script>
	<script src="/libs/clipboard.min.js"></script>
	<script src="/libs/masonry.pkgd.min.js"></script>
	<script src="/libs/imagesloaded.pkgd.min.js"></script>


	
</head>
