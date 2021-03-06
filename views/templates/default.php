<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Social Network</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="<?= URL ?>/ressources/css/style.css">
    <link rel="stylesheet" href="<?= URL ?>/ressources/css/form_co_ins.css">
    <!-- ICONS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://kit.fontawesome.com/68f3afb94b.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="<?= URL ?>/ressources/JS/function_input.js"></script>
    <script src="<?= URL ?>/ressources/JS/script.js"></script>
    <script src="<?= URL ?>/ressources/JS/reaction.js"></script>
    <script src="<?= URL ?>/ressources/JS/module_connexion.js"></script>
    <script src="<?= URL ?>/ressources/JS/inscription.js"></script>
    <script src="<?= URL ?>/ressources/JS/profil.js"></script>
    <script src="<?= URL ?>/ressources/JS/commentaire.js"></script>
    <script src="<?= URL ?>/ressources/JS/ajaxProfile.js"></script>
    <script src="ressources/js/messagerie.js"></script>
    <script src="<?= URL ?>/ressources/JS/setProfil.js"></script>
</head>
<body>
<header class="background-blue-grey box-shadow z-index-3">
    <?php include 'header.php'; ?>
</header>
<main class="flex-1 flex-column background-lighter-grey">
    <div class="max-width-content background-white box-shadow h-100 w-100 flex-1 flex-column">
        <?= $content ?>
    </div>

</main>
<footer class="background-lighter-grey box-shadow">
    <p class="text-center mx-auto bold-text grey-text">Social Network - Développé par Céline Pawlak, Martin Bozon, Maxime Siegl & Cécile Wojnowski</p>
</footer>
</body>
</html>
