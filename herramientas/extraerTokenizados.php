<?php

function defaultValue($valor,$default){
    return (empty($valor)?$default:$valor);
}
function tokens($csv){
    $salida = [];
    for ($i = 3; $i < count($csv); $i++) {
        $salida[] = $csv[$i];
    }
    return $salida;
}

$negs = fopen("../datos/negs.csv", "r");
$pos = fopen("../datos/pos.csv", "r");

$total = 25000;


while(($csv = fgetcsv($negs))){
    $review_id = $csv[0];
    $dataset = $csv[1];
    $clasificacion = $csv[2];
    $tokens = tokens($csv);
}
        