<?php

var_dump($_POST);

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo 'Bonjour ' . $_POST['nom'] . ' ' . $_POST['pnom'] . '<br>';
    echo ' Tu as ' . $_POST['age'] . ' ans.';
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Formulaire avec faille de sécurité</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <main class="centrage">
        <section class="cardLook cardForm">
            <h1>Formulaire avec faille de sécurité</h1>
            <!-- Formulaire -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <p><?php echo $message; ?></p>

                <label for="nom">Nom(*): </label>
                <input type="text" name="nom" id="nom" placeholder="Entrez votre nom" value="" />
                <label for="pnom">Prenom(*): </label>
                <input type="text" name="pnom" id="pnom" placeholder="Entrez votre prenom" value="" />
                <label for="age">Prenom(*): </label>
                <input type="text" name="age" id="age" placeholder="Entrez votre age" value="" />


                <input type="submit" name="btn_submit" value="Envoyer" />


            </form>
        </section>
    </main>
</body>

</html>