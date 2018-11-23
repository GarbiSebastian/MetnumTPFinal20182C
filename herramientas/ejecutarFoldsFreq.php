<?php

$folds = [25, 10, 5, 2];
$ks = [1, 3, 5, 11, 31, 41, 43, 45, 47, 49, 51, 61, 71, 91, 101];
$alfas = range(10, 200, 10);
$metodo = 1;
$freqsMin = range(0.05, 0.2, 0.01);
$freqsMax = range(0.3, 1.1, 0.1);

$alfas = [40];
$ks = [35];
$folds = [25];

foreach ($ks as $k) {
    foreach ($freqsMin as $min) {
        foreach ($freqsMax as $max) {
            foreach ($folds as $fold) {
                foreach ($alfas as $alfa) {
                    $dirResultado = "resultados/freqs/fmin-$min/fmax-$max/metodo-$metodo/knn-$k/alfa-$alfa/$fold-fold";
                    exec("mkdir -p $dirResultado");
                    foreach (range(1, $fold) as $iteracion) {
                        $dataset = "datos/fold/$fold-fold/iter-$iteracion.csv";
                        $clasif = "$dirResultado/clasif-$iteracion.csv";
                        $real = "$dirResultado/real-$iteracion.csv";
                        $extra = "$dirResultado/extra-$iteracion.txt";
                        if (!file_exists($extra)) {
                            exec("touch $extra");
                            $comandoKnn = "./tp"
                                    . " -m $metodo"
                                    . " -d $dataset"
                                    . " -o $clasif"
                                    . " -r $real"
                                    . " -k $k"
                                    . " -a $alfa"
                                    . " -x $extra"
                                    . " -fi $min"
                                    . " -fa $max"
                                    . " 2> /dev/null";
                            echo $comandoKnn . PHP_EOL;
                            exec($comandoKnn);
                        }
                    }
                }
            }
        }
    }
}
