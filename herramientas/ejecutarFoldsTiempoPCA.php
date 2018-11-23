<?php

$folds = [2, 5, 10, 25];
$ks = [1, 3, 5, 11, 31, 41, 43, 45, 47, 49, 51, 61, 71, 91, 101];
$alfas = range(10,100,10);


$folds = [25];


$metodo = 0;
$k = 35;

foreach ($folds as $fold) {
    foreach (range(1, $fold) as $iteracion) {
        $dataset = "datos/fold/$fold-fold/iter-$iteracion.csv";
        foreach ($alfas as $alfa) {
            $archivo = "resultados/velocidad/metodo-$metodo/velocidad-a$alfa-f$fold-i$iteracion.txt";
            if (!file_exists($archivo)) {
                $comandoKnn = "./tp"
                        . " -m $metodo"
                        . " -d $dataset"
                        . " -o /dev/null"
                        . " -r /dev/null"
                        . " -k $k"
                        . " -a $alfa"
                        . " -x $archivo"
                        . " 2> /dev/null"
                ;
                echo $comandoKnn . PHP_EOL;
                exec($comandoKnn);
            }
        }
    }
}