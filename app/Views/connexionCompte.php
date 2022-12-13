<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/connexionCompte.css"?>>
    <title>Hot genre</title>
</head>

<?php
	// On vérifie si on a eu une erreur lors de la création du compte et on l'affiche si besoin
	$erreurTexte = "";

	if ($compteNonExistant) {
		$erreurTexte = "Il ne semble pas d'y avoir de compte correspondant à cette adresse mail. Essayez de vous inscrire plutôt.";
	}

	if ($passwordFaux) {
		$erreurTexte = "Mot de passe incorrecte. Veuillez réessayez.";
	}
?>

<body>
	<div class="page_container">
		<div class="left_side_container">
			<img src="<?= site_url() . "images/compteImage.jpg" ?>">
		</div>

		<div class="right_side_container">
			<div class="form">

			<a href="<?= url_to('Home::index') ?>">
        <img src="<?= site_url() . "images/logos/logo hg noir.png" ?>" alt="Logo">
      </a>

			<h3>Se connecter</h3>

			<h5 class="erreur_texte"><?= ($erreurTexte != "") ? $erreurTexte : "" ?></h5>

			<form action=<?= url_to('Client::connexionCompte') ?> method="post">
				<div>
					<label for="email">Adresse email *</label>
					<input type="email" name="email" id="email" placeholder=" " maxlength="255" required/>
					<h5>Votre adresse email n'est pas valide</h5>
				</div>

				<div>
					<label for="password">Mot de passe *</label>
					<input type="password" name="password" id="password" placeholder=" " minlength="8" maxlength="64" required/>
					<h5>Votre mot de passe doit faire entre 8 et 64 caractères</h5>
				</div>

				<button type="submit">Je me connecte</button>

				<h4 class="connexion_compte">Vous n'avez pas encore de compte ? <a href="<?= url_to('Client::inscription') ?>">Inscrivez-vous</a> plutôt.</h4>
			</form>
		</div>
	</div>
</div>
</body>
</html>




<form action=<?= url_to('Client::connexionCompte') ?> method="post">
  <label for="email">Email :</label>
  <input type="email" id="email" placeholder="adresse mail" name="email" maxlength="255">

  <label for="password">Mot de passe :</label>
  <input type="password" id="password" name="password" maxlength="64">

  <input type="submit">
</form>

<a href="<?= url_to('Client::inscription') ?>">Inscription</a>