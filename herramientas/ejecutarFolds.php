<?php

$folds = [2, 5, 10, 25];
$ks = [1, 3, 5, 11, 31, 41, 43, 45, 47, 49, 51, 61, 71, 91, 101];
$folds = [25];

$metodo = 0;

foreach ($ks as $k) {
    foreach ($folds as $fold) {
        $dirResultado = "resultados/metodo-$metodo/knn-$k/$fold-fold";
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
                        . " -x $extra"
                        . " 2> /dev/null"
                ;
                echo $comandoKnn . PHP_EOL;
                exec($comandoKnn);
            }
        }
    }
}
