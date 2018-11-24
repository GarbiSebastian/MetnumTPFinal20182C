<?php

$fmins = range(0.05, 0.2, 0.01);
$fmaxs = range(0.3, 1.1, 0.1);

$arch = fopen("datos/vocab.csv", "r");
$palabras=[];
$dato = fgetcsv($arch);
while($dato = fgetcsv($arch)){
    $f = floatval($dato[3]);
    $c = $dato[4];
    if(0.05 < $f && $f < 0.9){
        //$palabras[] = [$c,$f];
        $palabras[] = $f;
    }
}
fclose($arch);

$salida = [["fmin","fmax","#pal"]];
foreach ($fmins as $min) {
    foreach ($fmaxs as $max) {
        $salida[] = [$min,$max,count(array_filter($palabras, function($d) use($min,$max){return $min < $d && $d < $max;}))];
    }
}

$arch = fopen("resultados/conteo.csv", "w");
foreach ($salida as $value) {
    fputcsv($arch, $value);
}
fclose($arch);