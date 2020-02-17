<?php
ini_set('display_errors','on');
error_reporting(E_ALL);
include_once './functions.php';
$content_repo = get_all_file_names();

if (!isset($_GET) || empty($_GET['view']) ||
    !in_array($_GET['view'] . '.json', $content_repo)) {
    
    header("Location: ./index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datacity - Accueil</title>

    <!-- <link rel="stylesheet" href="/nantarbourg/libs/bootstrap/css/bootstrap-grid.min.css"> -->
    <link rel="stylesheet" href="./libs/bootstrap/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="./libs/bootstrap/css/bootstrap.min.css">

    <!-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="p-2 col-sm-7">

    <?php
        $JSON_file = JSON_file_to_array('./data/' . $_GET['view'] . '.json');
    ?>

    <h1><?= $JSON_file['lieu'] ?> / <?= $JSON_file['categorie'] ?> / <?= $JSON_file['annee'] ?></h1>

    <a href="./index.php" class="btn btn-secondary my-2">Retour à l'accueil</a>

    <h2>Description</h2>

    <p><?= $JSON_file['remarques'] ?></p>
    <p>Mis à disposition par <?= $JSON_file['institution'] ?>.</p>

    <ul class="list-group my-2">
    <?php
    $all_fields = CSV_file_to_array('./fields.csv');
    $all_fields_name = CSV_file_to_array('./fields_name.csv');
    $i = 0;
    
    foreach ($JSON_file as $key => $value) {
        if (!in_array($key, $all_fields)) {
            continue;
        }
        
        switch ($value) {
            case 'yes':
                echo '<li class="list-group-item list-group-item-success">' . $all_fields_name[$i] . ' Oui</li>';
                break;
            case 'no':
                echo '<li class="list-group-item list-group-item-danger">' . $all_fields_name[$i] . ' Non</li>';
                break;
            case 'unsure':
                echo '<li class="list-group-item list-group-item-dark">' . $all_fields_name[$i] . ' Incertain</li>';
                break;
            default:
                echo '<li class="list-group-item list-group-item-dark">' . $all_fields_name[$i] . ' Incertain</li>';
                break;
        }
        $i++;
    }
    ?>
    </ul>

    <p>Données modifiées par <?= $JSON_file['contributor'] ?> le <?= $JSON_file['date_last_edit'] ?>.</p>

    <a href="<?= $JSON_file['data_loc'] ?>" target="_target" class="btn btn-primary my-2">Accéder aux données</a>

    <?php include_once './include/footer.html' ?>

    <!-- <script src="./libs/jquery.min.js"></script> -->
    <!-- <script src="./libs/popper.min.js"></script> -->
    <!-- <script src="./libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./libs/bootstrap/js/bootstrap.min.js"></script> -->

</body>

</html>