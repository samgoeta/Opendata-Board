<?php
function get_all_file_names() {
    $content_repo = scandir('./data/');
    $hidden_items = array('.', '..', '.DS_Store');
    $content_repo = array_diff($content_repo, $hidden_items);
    return $content_repo ;
}

function JSON_file_to_array($JSON_file_path) {
    $json_file = file_get_contents($JSON_file_path);
    $json_file = json_decode($json_file, true);
    return $json_file;
}

function CSV_file_to_array($CSV_file_path) {
    $csv_file = file_get_contents($CSV_file_path);
    $csv_file = str_getcsv($csv_file, ",");
    return $csv_file;
}

function find_all_keys($total_JSON_array, $key) {
    $tab = [];

    foreach ($total_JSON_array as $tab_index => $value) {
        array_push($tab, $value[$key]);
    }

    $tab = array_unique($tab);

    return $tab;
}

function find_infos($total_JSON_array, $ville, $categorie) {

    $tab_infos = [];

    $all_fields = CSV_file_to_array('./fields.csv');

    $id_file = '';

    foreach ($all_fields as $field) {
        foreach ($total_JSON_array as $tab_index => $value) {

            if ($value['categorie'] == $categorie && $value['lieu'] == $ville) {
                array_push($tab_infos, $value[$field]);
                $id_file = $value['id'];
            }
        }
    }

    $fields_view = '';
    $popover_content = '';
    $score = 0;

    $i = 0;

    $all_fields_name = CSV_file_to_array('./fields_name.csv');

    foreach ($tab_infos as $tab_index => $value) {
        switch ($value) {
            case true:
                $fields_view .= '<span class=\'field-color field-color--yes\'></span>';
                $popover_content .= '<li class=\'list-group-item list-group-item-success\'>' . $all_fields_name[$i] . ' : Oui</li>';
                $score++;
                break;
            case false:
                $fields_view .= '<span class=\'field-color field-color--no\'></span>';
                $popover_content .= '<li class=\'list-group-item list-group-item-danger\'>' . $all_fields_name[$i] . ' : Non</li>';
                break;
            default:
                $fields_view .= '<span class=\'field-color field-color--no-data\'></span>';
                $popover_content .= '<li class=\'list-group-item list-group-item-dark\'>' . $all_fields_name[$i] . ' : Incertain</li>';
                break;
        }

        $i++;
    }

    $html = '<a href="./view.php?view=' . $id_file . '"><div data-toggle="popover" data-trigger="hover" data-placement="right"
    title="Statistiques" data-content="<ul class=\'list-group || little-list-group\'>' . $popover_content . '<ul>">' . $fields_view . '</div></a>';

    return ['HTML' => $html, 'score' => $score];
}