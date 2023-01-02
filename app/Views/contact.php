<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/contact.css"?>>
    <title>Hot genre</title>

</head>
<body>
    <?php include 'header.php';?>

    <div class="contact_container">
        <h1>Contact</h1>

        <form class="contact" action=<?= url_to('ClientController::messageContact') ?> method="post">
            <div>
                <label for="from">Votre adresse mail *</label>
                <input type="email" name="from" id="from" placeholder=" " maxlength="70" required/>
            </div>
            
            <div>
                <label for="subject">Sujet *</label>
                <input type="text" name="subject" id="subject" placeholder=" " maxlength="70" required/>
            </div>

            <div>
                <label for="message">Message * (maximum : 1500 caractères)</label>
                <textarea id="message" name="message" maxlength="1500" rows="10"></textarea>
            </div>

            <button type="submit" class="contact_button">Envoyer</button>
        </form>
    </div>

    <?php include 'footer.php';?>
</body>
</html>