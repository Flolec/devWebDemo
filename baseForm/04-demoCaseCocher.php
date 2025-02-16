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

$erreur = '';
$cleanData = [];
$options = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Traitement et validation des cases à cocher
    if (isset($_POST['options']) && is_array($_POST['options']) && !empty($_POST['options'])) {
        $validOptions = ['vol', 'hotel', 'visite'];
        // Garde uniquement les options attendues
        $filteredOptions = array_intersect($_POST['options'], $validOptions);

        if (!empty($filteredOptions)) {
            $cleanData['options'] = array_map('sanitizeData', $filteredOptions);
        } else {
            $erreur = "L/les option(s) sélectionnée(s) n'est/sont pas valide(s).";
        }
    } else {
        $erreur = "Veuillez sélectionner au moins une option.";
    }

    // Assurer que $options est bien défini pour le réaffichage du formulaire
    $options = $cleanData['options'] ?? [];
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Démonstration formulaire : cases à cocher</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <main class="centrage">
        <section class="cardLook cardForm">
            <h1>Démonstration formulaire : cases à cocher</h1>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!-- Affichage des erreurs ou des résultats -->
                <?php if ($_SERVER["REQUEST_METHOD"] === "POST") { ?>
                    <div class="result">
                        <?php if (!empty($erreur)) { ?>
                            <p class="erreur">Erreur détectée : <?php echo $erreur; ?></p>
                        <?php } else { ?>
                            <p>Merci pour votre inscription.</p>
                            <p>Options sélectionnées : <?php echo implode(", ", $cleanData['options'] ?? []); ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>

                <fieldset>
                    <legend>Options de voyage</legend>
                    <!-- nommer les checkbox du formulaire avec des crochets [] -->
                    <label>
                        <input type="checkbox" name="options[]" value="vol" <?php if (in_array("vol", $options)) echo 'checked'; ?>>
                        Vol
                    </label>
                    <label>
                        <input type="checkbox" name="options[]" value="hotel" <?php if (in_array("hotel", $options)) echo 'checked'; ?>>
                        Hôtel
                    </label>
                    <label>
                        <input type="checkbox" name="options[]" value="visite" <?php if (in_array("visite", $options)) echo 'checked'; ?>>
                        Visite
                    </label>
                </fieldset>
                <input type="submit" name="btn_submit" value="Go !">
            </form>
        </section>
    </main>
</body>

</html>