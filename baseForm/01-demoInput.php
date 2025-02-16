<?php

var_dump($_POST);

/**
 * sanitizeData
 *
 * Nettoie et sécurise une chaîne de caractères en supprimant les espaces inutiles
 * et en encodant les caractères spéciaux pour prévenir les attaques XSS.
 *
 * @param string $data La chaîne de caractères à nettoyer.
 * @return string La chaîne de caractères nettoyée et sécurisée.
 */

function sanitizeData($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$erreur = "";
$nom = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Nettoyage et sécurisation des données
    $nom = sanitizeData($_POST["nom"] ?? ''); // identique à $nom = isset($_POST["nom"]) ? sanitizeData($_POST["nom"]) : '';

    // Vérification et validation du champ 'nom'
    if (empty($nom)) {
        $erreur = "Le champ 'Nom' est requis.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Démonstration formulaire : input</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <main class="centrage">
        <section class="cardLook cardForm">
            <h1>Démonstration formulaire : input</h1>
            <!-- Formulaire -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!-- Affichage des erreurs ou des résultats -->
                <?php if ($_SERVER["REQUEST_METHOD"] === "POST") { ?>
                    <div class="result">
                        <?php if (!empty($erreur)) { ?>
                            <p class="erreur">Erreur détectée : <?php echo $erreur; ?> </p>
                        <?php } else { ?>
                            <p>Merci <?php echo $nom; ?> pour votre inscription</p>
                        <?php } ?>
                    </div>
                <?php } ?>


                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>">

                <input type="submit" name="btn_submit" value="Go !">
            </form>
        </section>
    </main>
</body>

</html>