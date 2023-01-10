<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/changerMotDePasse.css"?>>
	<script src=<?= site_url() . "js_script/creerCompte.js"?>></script>
    <title>Hot genre</title>
</head>

<?php
	$erreurTexte = "";

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

            <a href="<?= url_to('Home::index') ?>">
                <img src="<?= site_url() . "images/logos/logo hg noir.png" ?>" alt="Logo">
            </a>

			<h3>Réinitialiser votre mot de passe</h3>

			<h5 class="erreur_texte"><?= ($erreurTexte != "") ? $erreurTexte : "" ?></h5>

			<form action=<?= url_to('ClientController::reinitialiserMotDePasse') ?> method="post">
			
				<input type="hidden" name="id_client" id="id_client" placeholder=" " value="<?= $idClient ?>" required/>

				<div>
					<label for="password">Nouveau mot de passe *</label>

					<div class="password_container">
					<input type="password" name="password" id="password" placeholder=" " minlength="8" maxlength="64" required/>

					<div class="toggle_password_visibility" onclick="togglePasswordVisibility1()">
							<img src="<?= site_url() . "images/icons/eye.png"?>" alt="Logo">
						</div>
					</div>

					<h5>Votre adresse email n'est pas valide</h5>
				</div>

				<div>
					<label for="passwordRepetition">Répétez votre nouveau mot de passe *</label>
					
					<div class="password_container">
						<input type="password" name="passwordRepetition" id="passwordRepetition" placeholder=" " minlength="8" maxlength="64" required/>

						<div class="toggle_password_visibility" onclick="togglePasswordVisibility2()">
								<img src="<?= site_url() . "images/icons/eye.png"?>" alt="Logo">
						</div>
					</div>

					<h5>Votre mot de passe doit faire entre 8 et 64 caractères</h5>
				</div>

				<button type="submit">Réinialiser mon mot de passe</button>
			</form>
		</div>
	</div>
</div>
</body>
</html>