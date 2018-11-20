<?php

function defaultValue($valor, $default) {
    return (empty($valor) ? $default : $valor);
}

$handleR = fopen("clasif_real.csv",'r');
$handleK = fopen("clasif_knn.csv",'r');
$handleP = fopen("clasif_pca.csv",'r');

$resultados = [
    'knn' => [
        'pos' => [
            'pos' => 0,
            'neg' => 0
        ],
        'neg' => [
            'pos' => 0,
            'neg' => 0
        ],
    ],
    'pca' => [
        'pos' => [
            'pos' => 0,
            'neg' => 0
        ],
        'neg' => [
            'pos' => 0,
            'neg' => 0
        ],
    ],
];
$total = 0;

while ($csvR = fgetcsv($handleR)) {
    $csvK = fgetcsv($handleK);
    $csvP = fgetcsv($handleP);
    $resultados['knn'][$csvR[1]][$csvK[1]]++;
    $resultados['pca'][$csvR[1]][$csvP[1]]++;
    $total++;
}
echo json_encode(['total'=>$total,'resultados'=>$resultados]);

fclose($handleR);
fclose($handleK);
fclose($handleP);