<?php
// var_dump($_POST); // Débogage supprimé en production

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom   = isset($_POST['nom'])  ? trim(htmlspecialchars($_POST['nom']))  : '';
    $pnom  = isset($_POST['pnom']) ? trim(htmlspecialchars($_POST['pnom'])) : '';
    $age   = isset($_POST['age'])  ? filter_var(trim($_POST['age']), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) : false;

    if (empty($nom) || empty($pnom) || $age === false) {
        $message = "Veuillez remplir tous les champs et entrer un âge valide (> 0) !";
    } else {
        $message = "Bonjour $pnom $nom ! <br> vous avez $age ans.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Formulaire de démo - Conservation état</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <main class="centrage">
        <section class="cardLook cardForm">
            <h1>Formulaire de démo - Conservation état</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <p><?php echo $message; ?></p>

                <label for="nom">Nom (*): </label>
                <input type="text" name="nom" id="nom" placeholder="Entrez votre nom"
                    value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" />

                <label for="pnom">Prénom (*): </label>
                <input type="text" name="pnom" id="pnom" placeholder="Entrez votre prénom"
                    value="<?php echo isset($_POST['pnom']) ? htmlspecialchars($_POST['pnom']) : ''; ?>" />

                <label for="age">Âge (*): </label>
                <input type="text" name="age" id="age" placeholder="Entrez votre âge"
                    value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>" />

                <input type="submit" name="btn_submit" value="Envoyer" />
            </form>
        </section>
    </main>
</body>

</html>