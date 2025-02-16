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
$destination = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $destination = sanitizeData($_POST["destination"] ?? ''); // identique à $destination = isset($_POST["destination"]) ? sanitizeData($_POST["destination"]) : '';

    if (empty($destination)) {
        $erreur = "La destination est requise.";
    } else {
        $validDestination = ['paris', 'londres', 'rome'];

        if (!in_array($destination, $validDestination, true)) {
            $erreur = "La destination n'est pas valide.";
            $destination = ''; // Réinitialisation pour éviter d'afficher une valeur invalide
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Démonstration formulaire : liste déroulante</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <main class="centrage">
        <section class="cardLook cardForm">
            <h1>Démonstration formulaire : liste déroulante</h1>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!-- Affichage des erreurs ou des résultats -->
                <?php if ($_SERVER["REQUEST_METHOD"] === "POST") { ?>
                    <div class="result">
                        <?php if (!empty($erreur)) { ?>
                            <p class="erreur">Erreur détectée : <?php echo $erreur; ?> </p>
                        <?php } else { ?>
                            <p>Merci pour votre inscription.</p>
                            <p>Destination : <?php echo $destination; ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>


                <fieldset>
                    <label for="destination">Destination :</label>
                    <select name="destination" id="destination">
                        <option value="">--Choisir une destination--</option>
                        <option value="paris" <?php if ($destination === "paris") echo 'selected'; ?>>Paris</option>
                        <option value="londres" <?php if ($destination === "londres") echo 'selected'; ?>>Londres</option>
                        <option value="rome" <?php if ($destination === "rome") echo 'selected'; ?>>Rome</option>
                    </select>
                </fieldset>
                <input type="submit" name="btn_submit" value="Go !">
            </form>
        </section>
    </main>
</body>

</html>