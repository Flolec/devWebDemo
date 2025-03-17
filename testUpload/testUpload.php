<?php
echo '<br><b>Contenu du tableau POST</b><br>';
echo "<pre>";
var_dump($_POST);
echo "</pre>";
echo '<br><b>Contenu du tableau FILES</b><br>';
echo "<pre>";
var_dump($_FILES);
echo "</pre>";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Upload</title>
</head>

<body>
    <h1>Test Formulaire</h1>
    <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post" class="formAdmin" enctype="multipart/form-data">
        <label for='nom'>Votre nom</label>
        <input type='text' name='nom'>
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
        <input type='file' name='fichierUpload' accept="image/*, .pdf, .docx">
        <input type='submit' name='Go !'>
    </form>
</body>

</html>