<?php

//const string paramMetodo            = "-m";
//const string paramDatasetPath       = "-d";
//const string paramClasificacion     = "-o";
////opcionales
//const string paramVocabPath         = "-v";
//const string paramClasificacionReal = "-r";
//const string paramDatosExtra        = "-x";
//const string paramFrecuencyMin      = "-fi";
//const string paramFrecuencyMax      = "-fa";
//const string paramK                 = "-k";
//const string paramAlfa              = "-a";

$folds = [25, 10, 5, 2];
//$ks = [1, 3, 5, 11, 31, 41, 49, 51, 61, 71, 91, 101];
$ks = [31, 33, 35, 37, 39, 41, 43, 45, 47, 49, 51, 1, 3, 5, 11, 61, 71, 91, 101];
$alfas = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
$metodo = 1;

foreach ($ks as $k) {
    foreach ($folds as $fold) {
        foreach ($alfas as $alfa) {
            foreach (range(1, $fold) as $iteracion) {
                $dirResultado = "resultados/metodo-$metodo/knn-$k/alfa-$alfa/$fold-fold";
                if (!file_exists("$dirResultado/clasif-$iteracion.csv")) {
                    exec("mkdir -p $dirResultado");
                    $comandoKnn = "./tp"
                            . " -m $metodo"
                            . " -d datos/fold/$fold-fold/iter-$iteracion.csv"
                            . " -o $dirResultado/clasif-$iteracion.csv"
                            . " -r $dirResultado/real-$iteracion.csv"
                            . " -k $k"
                            . " -a $alfa"
                            . " -x $dirResultado/extra-$iteracion.txt"
                            . " 2> /dev/null";
                    echo $comandoKnn . PHP_EOL;
                    exec($comandoKnn);
                }
            }
        }
    }
}