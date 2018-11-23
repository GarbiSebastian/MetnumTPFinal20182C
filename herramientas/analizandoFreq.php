<?php

function igualDouble($double, $igual) {
    return $igual - 0.0001 <= $double && $double <= $igual + 0.0001;
}

function filtro($valores) {
    return function ($dato) use ($valores) {
        foreach ($valores as $key => $value) {
            if (is_double($value)) {
                if (!igualDouble($dato[$key], $value)) {
                    return false;
                }
            } elseif ($dato[$key] != $value) {
                return false;
            }
        }
        return true;
    };
}

function promediar($label1, $label2, $array) {
    $count = count($array);
    $acum = [$label1, $label2, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    foreach ($array as $linea) {
        for ($i = 6; $i <= 13; ++$i) {
            $acum[$i - 3] += $linea[$i] / $count;
        }
    }
    return $acum;
}

$folds = [2, 5, 10, 25];
$ks = [1, 3, 5, 11, 31, 33, 35, 37, 39, 41, 43, 45, 47, 49, 51, 61, 71, 91, 101];
$alfas = range(10, 100, 10);
$fmins = range(0.05, 0.09, 0.01);
$fmaxs = range(0.6, 0.9, 0.1);

$archivo = fopen("resultados/agrupadosFreq.csv", 'r');
$datos = [];
while ($datos[] = fgetcsv($archivo));
fclose($archivo);

$archivo = fopen("resultados/conteo.csv", 'r');
$conteo = [];
while ($x = fgetcsv($archivo)){
    $conteo[$x[0]][$x[1]]=$x[2];
};
fclose($archivo);

$archivo = fopen("resultados/promediosFreq.csv", "w");
fputcsv($archivo, ["fqmin", "fqmax", "tam", "tiempo", "accuracy", "+precision", "+recall", "+f1score", "-precision", "-recall", "-f1score"]);
foreach ($fmins as $min) {
    foreach ($fmaxs as $max) {
        $array = promediar($min, $max, array_filter($datos, filtro([4 => 35, 5 => 40, 1 => "f25", 2 => $min, 3 => $max])));
        $array[2] = $conteo[(string)round($min,2)][(string)round($max,1)];
        fputcsv($archivo, $array);
    }
}
fclose($archivo);
