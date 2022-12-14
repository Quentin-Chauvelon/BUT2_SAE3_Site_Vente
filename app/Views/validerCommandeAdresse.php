<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hot genre</title>
</head>
<body>
    <h1><?= $montant ?></h1>
    <h1><?= $nombreArticles ?></h1>
    <h1><?= $idCommande ?></h1>

    <form action=<?= url_to('ClientController::adresseCommande') ?> method="post">
        <input name="rue" type="text">
        <input name="codePostal" type="text" pattern="[0-9]{5}">
        <input name="ville" type="text">
        <input name="idCommande" type="hidden" value="<?= $idCommande ?>" readonly>

        <button type="submit">UTILISER CETTE ADRESSE</button>
    </form>
</body>
</html>