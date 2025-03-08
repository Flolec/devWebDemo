<?php include   '../../inc/head.inc.php' ?>
<?php include   '../../inc/header.inc.php' ?>

<main class="centrage boxOmbre">

    <h1>Gestion des Articles</h1>
    <ul class="containerFlex">
        <li><i class="fa fa-arrow-left"></i> <a href="<?= BASE_URL ?>"> vers la liste des articles</a></li>
    </ul>
    <form action="new.php" method="POST" class="formAdmin">
        <h2>Nouvel article</h2>

        <div class="box-alert color-sucess">
            message de confirmation
        </div>

        <div class="box-alert color-danger">
            <ul>
                <li>erreur</li>
            </ul>
        </div>
        <!-- Pour tester, les attributs required ont été enlevés +  maxlength="100"  -->
        <label for id="titre">Titre *</label><input type="text" size="50" id="titre" name="titre" value="">
        <label for id="contenu">Contenu *</label><textarea name="contenu" id="contenu"></textarea>
        <input type="submit" name="btn_ajout" class="btn btn-theme">
    </form>
</main>


<?php include  '../../inc/footer.inc.php' ?>