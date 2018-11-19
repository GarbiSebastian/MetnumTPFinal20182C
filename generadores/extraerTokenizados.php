<?php

function defaultValue($valor,$default){
    return (empty($valor)?$default:$valor);
}

$handle = fopen("../datos/imdb_tokenized.csv", "r");
$maxReviews = 10000;

while(($csv = fgetcsv($handle))){
    $review_id = $csv[0];
    $dataset = $csv[1];
    $clasificacion = $csv[2];
}
        