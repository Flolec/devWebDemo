<?php


var_dump($_POST);
// Fonction de nettoyage personnalisée pour les chaînes de caractères
function sanitizeString($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

//Règles de validation pour chaque champ du formulaire
// pour chaque name du formulaire, on définit un tableau associatif avec les clés suivantes :       
// filter : le filtre à appliquer
// options : les options supplémentaires pour le filtre
// error : le message d'erreur à afficher si la validation échoue

$validationRules = [
    'nom' => [
        'filter'  => FILTER_CALLBACK,
        'options' => 'sanitizeString',
        'error'   => "Le champ Nom est requis."
    ],
    'pnom' => [
        'filter'  => FILTER_CALLBACK,
        'options' => 'sanitizeString',
        'error'   => "Le champ Prénom est requis."
    ],
    'age' => [
        'filter'  => FILTER_VALIDATE_INT, //FILTER_VALIDATE_INT renvoie un entier valide ou false
        'options' => ['options' => ['min_range' => 18]],
        'error'   => "Vous devez avoir 18 ans."
    ],
    'voyageType' => [
        'filter'  => FILTER_CALLBACK,
        'options' => function ($value) {
            return in_array(sanitizeString($value), ['aventure', 'loisir', 'culture']) ? sanitizeString($value) : false;
        },
        'error'   => "Le type de voyage sélectionné n'est pas valide."
    ],
    'destination' => [
        'filter'  => FILTER_CALLBACK,
        'options' => function ($value) {
            return in_array(sanitizeString($value), ['paris', 'londres', 'rome']) ? sanitizeString($value) : false;
        },
        'error'   => "La destination sélectionnée n'est pas valide."
    ],
    'options' => [
        'filter'  => FILTER_CALLBACK,
        'options' => function ($values) {
            if (!is_array($values) || empty($values)) {
                return false;
            }
            return array_map('sanitizeString', $values);
        },
        'error'   => "Veuillez sélectionner au moins une option."
    ]
];


//tableau qui contiendra les erreurs à afficher
$errors = [];

//tableau qui contiendra les données nettoyées
$cleanData = [];

$options = [];


// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Itération sur chaque règle pour valider et assainir les données
    //$field va contenir les clés du tableau $validationRules
    //$rule va contenir le tableau associé  à la clé $field
    foreach ($validationRules as $field => $rule) {

        /**
         * $_POST[$field] est défini et non nul ?
         * Si oui, la valeur correspondante est assignée à $value.
         * Sinon, $value reçoit la chaîne vide ''.
         */

        $value = isset($_POST[$field]) ? $_POST[$field] : ''; //version courte : $value = $_POST[$field] ?? '';
        /**
         * La condition if (isset($rule['options']) && $rule['filter'] === FILTER_CALLBACK) vérifie que la clé 'options' 
         * est définie dans la règle et que le filtre spécifié est FILTER_CALLBACK.
         * Cela signifie que pour ce champ, la validation se fera via une fonction de rappel (callback).
         * 
         * Si une option existe et que le filtre est FILTER_CALLBACK, on appelle la fonction de rappel avec la valeur du champ
         * Le résultat de cette fonction est stocké dans $filtered.
         * 
         * 
         * Si la condition est fausse (filtre n'est pas un callback),
         *  la fonction filter_var() est utilisée pour appliquer le filtre défini dans $rule['filter'] sur $value.
         * Les options supplémentaires pour le filtre, si elles existent, sont passées via $rule['options'] 
         * (sinon null est utilisé grâce à l'opérateur de coalescence ??).
         * 
         * Le résultat de cette fonction est stocké dans $filtered.
         */
        if (isset($rule['options']) && $rule['filter'] === FILTER_CALLBACK) {
            $filtered = call_user_func($rule['options'], $value);
        } else {
            $filtered = filter_var($value, $rule['filter'], $rule['options'] ?? null);
        }

        /**
         * Si $filtered est faux ou si c'est une chaîne vide, une erreur est ajoutée au tableau $errors.
         * Sinon, la valeur nettoyée est ajoutée au tableau $cleanData.
         */
        if ($filtered === false || (is_string($filtered) && trim($filtered) === '')) {
            $errors[$field] = $rule['error'];
            // Conserver la valeur brute mais en la nettoyant pour éviter XSS
            $cleanData[$field] = sanitizeString($_POST[$field] ?? '');
        } else {
            $cleanData[$field] = $filtered;
        }
    }
}

// Affectation des valeurs nettoyées pour réaffichage
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