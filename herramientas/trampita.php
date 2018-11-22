<?php

$folds = [2, 5, 10, 25];
$ks = [1, 3, 5, 11, 31, 51];

function calcularCasos($clasif, $real) {
    $c = fopen($clasif, 'r');
    $r = fopen($real, 'r');
    $res = ['neg' => ['neg' => 0, 'pos' => 0,], 'pos' => ['neg' => 0, 'pos' => 0,],];
    while ($csvC = fgetcsv($c)) {
        $csvR = fgetcsv($r);
        $res[$csvR[1]][$csvC[1]] ++;
    }
    fclose($c);
    fclose($r);
    return $res;
}

function primeraParte($k, $fold, $iter) {
    $clasif = "resultados/metodo-0/knn-$k/$fold-fold/clasif-$iter.csv";
    $real = "resultados/metodo-0/knn-$k/$fold-fold/real-$iter.csv";
    $extra = "resultados/metodo-0/knn-$k/$fold-fold/extra-$iter.txt";
    $oldextra = "resultados/metodo-0/knn-$k/$fold-fold/old-extra-$iter.txt";
    $testo = "parametros: metodo: kNN\n" .
            "dataset: datos/fold/$fold-fold/iter-$iter.csv\n" .
            "clasificación obtenida: $clasif\n" .
            "clasificación real: $real\n" .
            "k vecinos: $k\n" .
            "alfa componentes: 50\n" .
            "rango frecuencia: ( 0.1 ; 0.5 )\n\n";
    return [$clasif, $real, $extra, $oldextra, $testo];
}

function precision($tp, $fn, $fp, $tn) {
    return $tp / ($tp + $fp);
}

function recall($tp, $fn, $fp, $tn) {
    return $tp / ($tp + $fn);
}

function f1score($tp, $fn, $fp, $tn) {
    $p = precision($tp, $fn, $fp, $tn);
    $r = recall($tp, $fn, $fp, $tn);
    return 2 * $p * $r / ($p + $r);
}

function ultimaParte($res) {
    $tp = $res['pos']['pos'];
    $fp = $res['neg']['pos'];
    $fn = $res['pos']['neg'];
    $tn = $res['neg']['neg'];

    $testo = "Positivos\n" .
            "tp: $tp\n" .
            "fn: $fn\n" .
            "fp: $fp\n" .
            "tn: $tn\n" .
            "precision: " . round(precision($tp, $fn, $fp, $tn), 6) . "\n" .
            "recall: " . round(recall($tp, $fn, $fp, $tn), 6) . "\n" .
            "f1-score: " . round(f1score($tp, $fn, $fp, $tn), 6) . "\n" .
            "\n" .
            "Negativos\n" .
            "tp: $tn\n" .
            "fn: $fp\n" .
            "fp: $fn\n" .
            "tn: $tp\n" .
            "precision: " . round(precision($tn, $fp, $fn, $tp), 6) . "\n" .
            "recall: " . round(recall($tn, $fp, $fn, $tp), 6) . "\n" .
            "f1-score: " . round(f1score($tn, $fp, $fn, $tp), 6) . "\n";
    return $testo;
}

foreach ($ks as $k) {
    foreach ($folds as $fold) {
        foreach (range(1, $fold) as $iter) {
            list($clasif, $real, $extra, $oldextra, $testo1) = primeraParte($k, $fold, $iter);
            $matches = [];
            $viejo = file_get_contents($extra);
            file_put_contents($oldextra, $viejo);
            preg_match("/tiempo: (?<tiempo>\d+(\.\d+)?)/", $viejo, $matches);
            $tiempo = $matches["tiempo"];
            $testo2 = "tiempo: $tiempo\n";
            $res = calcularCasos($clasif, $real);
            $testo3 = ultimaParte($res);
            file_put_contents($extra, $testo1 . $testo2 . $testo3);
        }
    }
}
