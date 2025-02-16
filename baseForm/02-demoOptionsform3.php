<?php

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
$voyageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation du champ 'voyageType'
    if (isset($_POST["voyageType"]) && !empty($_POST['voyageType'])) {
        $voyageType = sanitizeData($_POST['voyageType']);

        $validVoyageTypes = ['aventure', 'loisir', 'culture'];
        if (!in_array($voyageType, $validVoyageTypes, true)) {
            $erreur = "Le type de voyage sélectionné n'est pas valide.";
        }
    } else {
        $erreur = "Le champ Type de voyage est requis.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $voyageType = sanitizeData($_POST["voyageType"] ?? ''); // identique à $voyageType = isset($_POST["voyageType"]) ? sanitizeData($_POST["voyageType"]) : '';

    if (empty($voyageType)) {
        $erreurs = "Le champ Type de voyage est requis.";
    } else {
        $validVoyageTypes = ['aventure', 'loisir', 'culture'];

        if (!in_array($voyageType, $validVoyageTypes, true)) {
            $erreurs = "Le type de voyage sélectionné n'est pas valide.";
            $voyageType = ''; // Réinitialisation pour éviter d'afficher une valeur invalide
        }
    }
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Démonstration formulaire : bouton radio</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <main class="centrage">
        <section class="cardLook cardForm">
            <h1>Démonstration formulaire : bouton radio</h1>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!-- Affichage des erreurs ou des résultats -->
                <?php if ($_SERVER["REQUEST_METHOD"] === "POST") { ?>
                    <div class="result">
                        <?php if (!empty($erreur)) { ?>
                            <p class="erreur">Erreur détectée : <?php echo $erreur; ?> </p>
                        <?php } else { ?>
                            <p>Merci pour votre inscription.</p>
                            <p>Type de voyage : <?php echo $voyageType; ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>

                <fieldset>
                    <legend>Type de voyage</legend>
                    <label>
                        <input type="radio" name="voyageType" value="aventure" <?php if ($voyageType === "aventure") echo 'checked'; ?>>
                        Aventure
                    </label>
                    <label>
                        <input type="radio" name="voyageType" value="loisir" <?php if ($voyageType === "loisir") echo 'checked'; ?>>
                        Loisir
                    </label>
                    <label>
                        <input type="radio" name="voyageType" value="culture" <?php if ($voyageType === "culture") echo 'checked'; ?>>
                        Culture
                    </label>
                </fieldset>
                <input type="submit" name="btn_submit" value="Go !">
            </form>
        </section>
    </main>
</body>

</html>