<?php

$folds = [25, 10, 5, 2];
$ks = [1, 3, 5, 11, 31, 41, 49, 51, 61, 71, 91, 101];
$alfas = range(10, 100, 10);
$metodo = 1;
$ks = [35];

foreach ($ks as $k) {
    foreach ($folds as $fold) {
        foreach ($alfas as $alfa) {
            $dirResultado = "resultados/metodo-$metodo/knn-$k/alfa-$alfa/$fold-fold";
            exec("mkdir -p $dirResultado");
            foreach (range(1, $fold) as $iteracion) {
                $dataset = "datos/fold/$fold-fold/iter-$iteracion.csv";
                $clasif = "$dirResultado/clasif-$iteracion.csv";
                $real = "$dirResultado/real-$iteracion.csv";
                $extra = "$dirResultado/extra-$iteracion.txt";
                if (!file_exists($extra)) {
                    $comandoKnn = "./tp"
                            . " -m $metodo"
                            . " -d $dataset"
                            . " -o $clasif"
                            . " -r $real"
                            . " -k $k"
                            . " -a $alfa"
                            . " -x $extra"
                            . " 2> /dev/null"
                    ;
                    echo $comandoKnn . PHP_EOL;
                    exec($comandoKnn);
                }
            }
        }
    }
}
