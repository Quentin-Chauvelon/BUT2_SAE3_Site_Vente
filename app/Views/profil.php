<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src=<?= site_url() . "js_script/profil.js"?>></script>
	<link rel="stylesheet" href=<?= site_url() . "css/profil.css"?>>
	<title>Hot genre</title>
</head>


<script>
	function ModifierProfil() {
		setTimeout(() => {
			ShowPopUp();
		}, 1000)
	}
</script>


<?php
	// On vérifie si on a eu une erreur lors de la création du compte et on l'affiche si besoin
	$erreurTexte = "";

	if ($emailDejaUtilise) {
		$erreurTexte = "Un compte semble déjà exister avec cette adresse mail. Veuillez essayer avec une autre adresse.";
		
		echo "<script>ModifierProfil();</script>";
	}
?>



<body>
    <h2>Mes informations personnelles :</h2>

    <ul>
        <li>Prénom : <?= $session["prenom"] ?></li>
        <li>Nom : <?= $session["nom"] ?></li>
        <li>Adresse email : <?= $session["email"] ?></li>
    </ul>

    <button onclick="ShowPopUp()" class="modifier_profil" type="submit">Modifier mes informations</button>

    <div id="modifier_profil_pop-up" class="modifier_profil_pop-up">
		<button onclick="ClosePopUp()" id="close_pop-up" class="close_pop-up">X</button>

        <h3>Modifier mes informations personnelles</h3>
        
        <h5 class="erreur_texte"><?= ($erreurTexte != "") ? $erreurTexte : "" ?></h5>

        <form action=<?= url_to('ClientController::modifierProfil') ?> method="post">
				<div>
					<label for="prenom">Prénom</label>
					<input type="text" name="prenom" id="prenom" placeholder="<?= $session["prenom"] ?>" maxlength="64"/>
					<h5>Votre prénom n'est pas valide</h5>
				</div>

				<div>
					<label for="nom">Nom</label>
					<input type="text" name="nom" id="nom" placeholder="<?= $session["nom"] ?>" maxlength="255"/>
					<h5>Votre nom n'est pas valide</h5>
				</div>

				<div>
					<label for="email">Adresse email</label>
					<input type="email" name="email" id="email" placeholder="<?= $session["email"] ?>" maxlength="255"/>
					<h5>Votre adresse email n'est pas valide</h5>
				</div>

				<button type="submit">Modifier</button>
			</form>
    </div>
</body>
</html>