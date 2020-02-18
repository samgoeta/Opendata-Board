<?php
// import des fonctions PHP
ini_set('display_errors','on');
error_reporting(E_ALL);
include_once './functions.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datacity - Accueil</title>

    <!-- LIBRAIRIES -->
    <link rel="stylesheet" href="./libs/bootstrap/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="./libs/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="./libs/datatables.css">
    <link rel="stylesheet" href="./assets/style.css">
</head>

<body class="col-lg-10">

    <?php
    // Obtenir le nom de tous les fichiers contenus dans le repertoire 'data'
    $content_repo = get_all_file_names('./data/');
    
    // Avec chacun des fichiers, remplir le tableau '$all_JSON_content',
    // il contient dorénavant l'ensemble de la data issue de tous les fichiers
    $all_JSON_content = [];
    foreach ($content_repo as $tab_index => $JSON_file_name):
        array_push($all_JSON_content, JSON_file_to_array('./data/' . $JSON_file_name));
    endforeach;

    // Extraire la liste des catégories et villes stockées,
    // en fonction de leur clé
    $tab_categories = find_all_keys($all_JSON_content, 'categorie');
    $tab_villes = find_all_keys($all_JSON_content, 'lieu');
    ?>

    <h1>Data census France</h1>

    <p><?= count($content_repo); ?> jeux de données analysés pour <?= count($tab_villes); ?> villes.</p>

    <table class="table" id="tab">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Ville</th>
                <?php foreach ($tab_categories as $categorie): ?>
                <th><?= $categorie ?></th>
                <?php endforeach; ?>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tab_villes as $ville): ?>
            <tr>
                <th scope="row"><?= $ville ?></th>
                <?php foreach ($tab_categories as $categorie): ?>
                <td class="sum-col">
                    <?php
                    $infos = find_infos($all_JSON_content, $ville, $categorie);
                    echo $infos['HTML']; // lien et Poppover du set de données
                    echo $infos['score']; // score du set de données
                    ?>
                </td>
                <?php endforeach; ?>
                <th class="total-col"></th>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php include_once './include/footer.html' ?>

    <!-- LIBRAIRIES -->
    <script src="./libs/jquery.min.js"></script>
    <script src="./libs/popper.min.js"></script>
    <script src="./libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>

    <script src="./assets/table.js"></script>

    <script>
        // actionvation du tableau
        $(document).ready( function () {
            var table = $('#tab').DataTable({
                paging: false
            });            
        });

        // activation des Poppovers
        $('[data-toggle="popover"]').popover({ trigger: "hover", html:true });
    </script>

</body>

</html>