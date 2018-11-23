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
$ks = [1, 3, 5, 11, 31, 41, 43, 45, 47, 49, 51, 61, 71, 91, 101];
//$ks = [39, 37, 35, 33];
$folds = [25];

$metodo = 0;

foreach ($ks as $k) {
    foreach ($folds as $fold) {
        foreach (range(1, $fold) as $iteracion) {
            $dirResultado = "resultados/metodo-$metodo/knn-$k/$fold-fold";
            exec("mkdir -p $dirResultado");
            $comandoKnn = "./tp"
                    . " -m $metodo"
                    . " -d datos/fold/$fold-fold/iter-$iteracion.csv"
                    //. " -o $dirResultado/clasif-$iteracion.csv"
                    . " -o /dev/null"
//                    . " -r dirResultado/real-$iteracion.csv"
                    . " -r /dev/null"
                    . " -k $k"
                    . " -x $dirResultado/extra-$iteracion.txt"
                    . " 2> /dev/null"
            ;
            echo $comandoKnn . PHP_EOL;
            exec($comandoKnn);
        }
    }
}
