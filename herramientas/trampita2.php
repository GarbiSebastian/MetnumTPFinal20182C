<?php

$folds = [2, 5, 10, 25];
$ks = [3, 5, 11, 31, 47, 49, 51, 71, 91, 101];
$ks = [11];

foreach ($ks as $k) {
    foreach ($folds as $fold) {
        $dir = "resultados/metodo-0/knn-$k/$fold-fold";
        foreach (range(1, $fold) as $iter) {
            $archivo = "$dir/extra-$iter.txt";
            if (file_exists($archivo)) {
                $contenido = file_get_contents($archivo);
                $matches = [];
                preg_match("/tiempo: (?<tiempo>\d+(\.\d+)?)/", $contenido, $matches);
                $tiempo = $matches["tiempo"]-10;
                //var_dump($tiempo);
                $nuevoContenido = preg_replace("/tiempo: \d+(\.\d+)?/i","tiempo: $tiempo",$contenido);
               // echo $nuevoContenido;
                //file_put_contents("$archivo",$nuevoContenido);
            }
        }
    }
}
