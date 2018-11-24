<?php

$folds = [2, 5, 10, 25];
$ks = [1, 3, 5, 11, 31, 33, 35, 37, 39, 41, 43, 45, 47, 49, 51, 61, 71, 91, 101];
$alfas = range(10,100,10);

$folds = [25];

$metodo = 0;

/*foreach ($folds as $fold) {
    foreach (range(1, $fold) as $iteracion) {
        foreach ($ks as $k) {
            $archivoViejo = "resultados/velocidad/metodo-$metodo/velocidad-$k-$fold-$iteracion.txt";
            $archivoNuevo = "resultados/velocidad/metodo-$metodo/velocidad-k$k-f$fold-i$iteracion.txt";
            if (file_exists($archivoViejo)) {
                file_put_contents($archivoNuevo, file_get_contents($archivoViejo));
            }
        }
    }
}*/

foreach ($folds as $fold) {
//    foreach (range(1, $fold) as $iteracion) {
    foreach (range(1, 13) as $iteracion) {
        $dataset = "datos/fold/$fold-fold/iter-$iteracion.csv";
        foreach ($ks as $k) {
            $archivo = "resultados/velocidad/metodo-$metodo/velocidad-k$k-f$fold-i$iteracion.txt";
            if (!file_exists($archivo)) {
                $comandoKnn = "./tp"
                        . " -m $metodo"
                        . " -d $dataset"
                        . " -o /dev/null"
                        . " -r /dev/null"
                        . " -k $k"
                        . " -x $archivo"
                        . " 2> /dev/null"
                ;
                echo $comandoKnn . PHP_EOL;
                exec($comandoKnn);
            }
        }
    }
}