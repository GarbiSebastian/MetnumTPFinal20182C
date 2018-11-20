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

$folds = [2, 5, 10, 25];
//$ks = [1, 3, 5, 11, 31, 51];
$ks = [11, 31, 51];
$alfas = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 200];
$metodo = 0;

foreach ($ks as $k) {
    foreach ($folds as $fold) {
        foreach (range(1, $fold) as $iteracion) {
            $dirResultado = "resultados/metodo-$metodo/knn-$k/$fold-fold";
            exec("mkdir -p $dirResultado");
            $comandoKnn = "./tp"
                    . " -m $metodo"
                    . " -d datos/fold/$fold-fold/iter-$iteracion.csv"
                    . " -o $dirResultado/clasif-$iteracion.csv"
                    . " -r $dirResultado/real-$iteracion.csv"
                    . " -k $k"
                    . " -x $dirResultado/extra-$iteracion.txt";
            echo $comandoKnn . PHP_EOL;
            exec($comandoKnn);
        }
    }
}
