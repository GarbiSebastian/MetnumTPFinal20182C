<?php

$folds = [2, 5, 10, 25];
$ks = [1, 3, 5, 11, 31, 33, 35, 37, 39, 41, 43, 45, 47, 49, 51, 61, 71, 91, 101];
$alfas = range(10, 100, 10);

$folds = [25];

$metodo = 0;
$min = 0.1;
$max = 0.5;
$maxIter = 13;
$alfa = 40;


$salida = [];
$salida[] = ["metodo", "fold", "freqMin", "freqMax", "k", "alfa", "tiempo"];

foreach ($folds as $fold) {
//    foreach (range(1, $fold) as $iteracion) {
    foreach ($ks as $k) {
        $tiempo = 0;
        foreach (range(1, $maxIter) as $iteracion) {
            $archivo = "resultados/velocidad/metodo-$metodo/velocidad-k$k-f$fold-i$iteracion.txt";
            $matches = [];
            preg_match("/tiempo: (?<tiempo>\d+(\.\d+)?)/", file_get_contents($archivo), $matches);
            $tiempo += (double) ($matches["tiempo"])/$maxIter;
        }
        $salida[] = ["m$metodo","f$fold",(string)(round($min, 2)),(string)(round($max, 2)),$k,$alfa, $tiempo];
    }
}
$arch = fopen("resultados/tiempos-m$metodo-variacion-k.csv", "w");
foreach ($salida as $linea) {
    fputcsv($arch, $linea);
}
fclose($arch);