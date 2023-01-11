<?php
require_once (APPPATH  . 'Controllers' . DIRECTORY_SEPARATOR . 'GetController.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/creerCompte.css"?>>
	<!-- <script src="https://www.google.com/recaptcha/enterprise.js?render=6LcDO-sjAAAAACCLute0JPUrvTLRch1GqTy5myDd"></script> -->
	<!-- <script src="https://www.google.com/recaptcha/api.js?render=6LcDO-sjAAAAACCLute0JPUrvTLRch1GqTy5myDd"></script> -->
    <script src=<?= site_url() . "js_script/creerCompte.js"?>></script>
    <title>Hot genre</title>
</head>

<?php
	// On vérifie si on a eu une erreur lors de la création du compte et on l'affiche si besoin
	$erreurTexte = "";

	if ($compteDejaExistant) {
		$erreurTexte = "Un compte semble déjà exister avec cette adresse mail. Essayez de vous <a href=".url_to('ClientController::connexion').">connecter</a> plutôt.";
	}

	if ($passwordsDifferents) {
		$erreurTexte = "Les mots de passe que vous avez indiqués sont différents. Veuillez réessayez.";
	}
?>

<body>
	<div class="page_container">
		<div class="left_side_container">
			<img src="<?= site_url() . "images/compteImage.jpg" ?>">
		</div>

		<div class="right_side_container">
			<div class="form">

			<a href="<?= url_to(getRoute("index")) ?>">
            	<img src="<?= site_url() . "images/logos/logo hg noir.png" ?>" alt="Logo">
        	</a>

			<h3>Créer un compte</h3>

			<h5 class="erreur_texte"><?= ($erreurTexte != "") ? $erreurTexte : "" ?></h5>

			<form action=<?= url_to(getRoute("creerCompte")) ?> method="post">
				<div>
					<label for="prenom">Prénom *</label>
					<input type="text" name="prenom" id="prenom" placeholder=" " maxlength="64" required/>
					<h5>Votre prénom n'est pas valide</h5>
				</div>

				<div>
					<label for="nom">Nom *</label>
					<input type="text" name="nom" id="nom" placeholder=" " maxlength="255" required/>
					<h5>Votre nom n'est pas valide</h5>
				</div>

				<div>
					<label for="email">Adresse email *</label>
					<input type="email" name="email" id="email" placeholder=" " maxlength="255" required/>
					<h5>Votre adresse email n'est pas valide</h5>
				</div>

				<div>
					<label for="password">Mot de passe *</label>

					<div class="password_container">
						<input type="password" name="password" id="password" placeholder=" " minlength="8" maxlength="64" required/>

						<div class="toggle_password_visibility" onclick="togglePasswordVisibility1()">
								<img src="<?= site_url() . "images/icons/eye.png"?>" alt="Logo">
						</div>
					</div>

					<h5>Votre mot de passe doit faire entre 8 et 64 caractères</h5>
				</div>

				<div>
					<label for="passwordRepetition">Répétez votre mot de passe *</label>

					<div class="password_container">
						<input type="password" name="passwordRepetition" id="passwordRepetition" placeholder=" " minlength="8" maxlength="64" required/>

						<div class="toggle_password_visibility" onclick="togglePasswordVisibility2()">
							<img src="<?= site_url() . "images/icons/eye.png"?>" alt="Logo">
						</div>
					</div>

					<h5>Votre mot de passe doit faire entre 8 et 64 caractères</h5>
				</div>

				<div>
					<div class="cgu_checkbox">
						<input class="checkbox" type="checkbox" name="cgu" id="cgu" placeholder=" " required/>
						<label>J'accepte les <a href="<?= url_to(getRoute("cgu")) ?>">conditions générales d'utilisation</a></label>
					</div>
				</div>

				<div>
					<div class="rester_connecte">
						<input class="checkbox" type="checkbox" name="rester_connecte" id="rester_connecte" value="rester_connecte" placeholder=" "/>
						<label>Rester connecté</label>
					</div>
				</div>

				<div class="g-recaptcha" data-sitekey="6LcDO-sjAAAAACCLute0JPUrvTLRch1GqTy5myDd"></div>

				<button type="submit">Je crée mon compte</button>

				<h4 class="creer_compte">Vous avez déjà un compte ? Essayez de vous <a href="<?= url_to(getRoute("connexion")) ?>">connecter</a> plutôt.</h4>
			</form>
		</div>
	</div>
</div>
</body>
</html>
