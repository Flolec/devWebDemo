<?php
// Fonction de nettoyage personnalisée pour les chaînes de caractères
function sanitizeString($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$errors = [];
$cleanData = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation du champ 'nom'
    if (isset($_POST['nom']) && !empty($_POST['nom'])) {
        $cleanData['nom'] = sanitizeString($_POST['nom']);
    } else {
        $errors['nom'] = "Le champ Nom est requis.";
    }

    // Validation du champ 'pnom'
    if (isset($_POST['pnom']) && !empty($_POST['pnom'])) {
        $cleanData['pnom'] = sanitizeString($_POST['pnom']);
    } else {
        $errors['pnom'] = "Le champ Prénom est requis.";
    }

    // Validation du champ 'age'
    if (isset($_POST['age']) && !empty($_POST['age'])) {
        //FILTER_VALIDATE_INT renvoie un entier valide ou false
        $age = filter_var($_POST['age'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 18]]);
        if ($age === false) {
            $errors['age'] = "Vous devez avoir au moins 18 ans.";
            $cleanData['age'] = sanitizeString($_POST['age']);
        } else {
            $cleanData['age'] = $age;
        }
    } else {
        $errors['age'] = "Le champ âge est requis.";
    }

    // Validation du champ 'voyageType'
    if (isset($_POST['voyageType']) && !empty($_POST['voyageType'])) {
        $voyageType = sanitizeString($_POST['voyageType']);

        if (in_array($voyageType, ['aventure', 'loisir', 'culture'])) {
            $cleanData['voyageType'] = $voyageType;
        } else {
            $errors['voyageType'] = "Le type de voyage sélectionné n'est pas valide.";
        }
    } else {
        $errors['voyageType'] = "Le champ Type de voyage est requis.";
    }

    // Validation du champ 'destination'
    if (isset($_POST['destination']) && !empty($_POST['destination'])) {
        $destination = sanitizeString($_POST['destination']);
        if (in_array($destination, ['paris', 'londres', 'rome'])) {
            $cleanData['destination'] = $destination;
        } else {
            $errors['destination'] = "La destination sélectionnée n'est pas valide.";
        }
    } else {
        $errors['destination'] = "Le champ Destination est requis.";
    }


    // Validation du champ 'options'
    if (isset($_POST['options']) && is_array($_POST['options']) && !empty($_POST['options'])) {
        $validOptions = ['vol', 'hotel', 'visite'];
        // Vérifie que toutes les options sélectionnées sont valides
        $filteredOptions = array_intersect($_POST['options'], $validOptions);

        if (!empty($filteredOptions)) {
            $cleanData['options'] = array_map('sanitizeString', $filteredOptions);
        } else {
            $errors['options'] = "L/les option(s) sélectionnée(s) n'est/sont pas valide(s).";
        }
    } else {
        $errors['options'] = "Veuillez sélectionner au moins une option.";
    }
}

$nom = $cleanData['nom'] ?? '';
$pnom = $cleanData['pnom'] ?? '';
$age = $cleanData['age'] ?? '';
$voyageType = $cleanData['voyageType'] ?? '';
$destination = $cleanData['destination'] ?? '';
$options = $cleanData['options'] ?? [];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Démonstration formulaire</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <main class="centrage">
        <section class="cardLook cardForm">
            <h1>Démonstration formulaire</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <?php if ($_SERVER["REQUEST_METHOD"] === "POST") { ?>
                    <div class="result">
                        <?php if (!empty($errors)) { ?>
                            <p class="erreur">Erreurs détectées :</p>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li class="erreur"><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php } else { ?>
                            <p>Merci <?php echo $nom; ?> <?php echo $pnom; ?> pour votre inscription.</p>
                            <ul>
                                <li>Age : <?php echo $age; ?></li>
                                <li>Options sélectionnées : <?php echo is_array($options) ? implode(", ", $options) : ''; ?></li>
                                <li>Type de voyage : <?php echo $voyageType; ?></li>
                                <li>Destination : <?php echo $destination; ?></li>
                            </ul>
                        <?php } ?>
                    </div>
                <?php } ?>
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>">
                <label for="pnom">Prénom :</label>
                <input type="text" id="pnom" name="pnom" value="<?php echo $pnom; ?>">
                <label for="age">Age :</label>
                <input type="text" id="age" name="age" value="<?php echo $age; ?>">

                <fieldset>
                    <legend>Options de voyage</legend>
                    <label>
                        <input type="checkbox" name="options[]" value="vol" <?php if (is_array($options) && in_array("vol", $options)) echo 'checked'; ?>>
                        Vol
                    </label>
                    <label>
                        <input type="checkbox" name="options[]" value="hotel" <?php if (is_array($options) && in_array("hotel", $options)) echo 'checked'; ?>>
                        Hôtel
                    </label>
                    <label>
                        <input type="checkbox" name="options[]" value="visite" <?php if (is_array($options) && in_array("visite", $options)) echo 'checked'; ?>>
                        Visite
                    </label>
                </fieldset>

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

                <label for="destination">Destination :</label>
                <select name="destination" id="destination">
                    <option value="">--Choisir une destination--</option>
                    <option value="paris" <?php if ($destination === "paris") echo 'selected'; ?>>Paris</option>
                    <option value="londres" <?php if ($destination === "londres") echo 'selected'; ?>>Londres</option>
                    <option value="rome" <?php if ($destination === "rome") echo 'selected'; ?>>Rome</option>
                </select>

                <input type="submit" name="btn_submit" value="Go !">
            </form>
        </section>
    </main>
</body>

</html>