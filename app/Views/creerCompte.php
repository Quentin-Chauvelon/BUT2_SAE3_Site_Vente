<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/creerCompte.css"?>>
    <title>Hot genre</title>
</head>

<?php
	// On vérifie si on a eu une erreur lors de la création du compte et on l'affiche si besoin
	$erreurTexte = "";

	if ($compteDejaExistant) {
		$erreurTexte = "Un compte semble déjà exister avec cette adresse mail. Essayez de vous connecter plutôt.";
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

			<a href="<?= url_to('Home::index') ?>">
            	<img src="<?= site_url() . "images/logos/logo hg noir.png" ?>" alt="Logo">
        	</a>

			<h3>Créer un compte</h3>

			<h5 class="erreur_texte"><?= ($erreurTexte != "") ? $erreurTexte : "" ?></h5>

			<form action=<?= url_to('Client::creerCompte') ?> method="post">
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
					<input type="password" name="password" id="password" placeholder=" " minlength="8" maxlength="64" required/>
					<h5>Votre mot de passe doit faire entre 8 et 64 caractères</h5>
				</div>

				<div>
					<label for="passwordRepetition">Répétez votre mot de passe *</label>
					<input type="password" name="passwordRepetition" id="passwordRepetition" placeholder=" " minlength="8" maxlength="64" required/>
					<h5>Votre mot de passe doit faire entre 8 et 64 caractères</h5>
				</div>

				<div>
					<div class="cgu_checkbox">
						<input class="checkbox" type="checkbox" name="titre" id="titre" placeholder=" " required/>
						<label>J'accepte les conditions générales d'utilisation</label>
					</div>
					<h5>Hint or Error Message</h5>
				</div>

				<button type="submit">Je crée mon compte</button>

				<h4 class="creer_compte">Vous avez déjà un compte ? Essayez de vous <a href="<?= url_to('Client::connexion') ?>">connecter</a> plutôt.</h4>

				<!-- prenom trop long, nom trop long, adresse mail non valide (html) -->
				<!-- mot de pase trop court, trop long, répéter deux fois les mêmes et s'assurer qu'ils sont égaux -->
				<!-- adresse (adresse, code postal, ville, pays => liste) s'assurer qu'ils sont bons -->

			</form>
		</div>
	</div>
</div>



    <!-- <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" placeholder="Prénom" name="prenom" maxlength="64" required>

    <label for="nom">Nom :</label>
    <input type="text" id="nom" placeholder="Nom" name="nom" maxlength="255" required>
    
    <label for="email">Email :</label>
    <input type="email" id="email" placeholder="adresse mail" name="email" maxlength="255" required>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" maxlength="64" required>

    <input type="submit"> -->

    
</body>
</html>