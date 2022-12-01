<form action=<?= url_to('Client::creerCompte') ?> method="post">
  <label for="prenom">Prénom :</label>
  <input type="text" id="prenom" placeholder="Prénom" name="prenom" maxlength="64">

  <label for="nom">Nom :</label>
  <input type="text" id="nom" placeholder="Nom" name="nom" maxlength="255">
  
  <label for="mail">Email :</label>
  <input type="email" id="mail" placeholder="adresse mail" name="mail" maxlength="255">

  <label for="password">Mot de passe :</label>
  <input type="password" id="password" name="password" maxlength="64">

  <input type="submit">
</form>
