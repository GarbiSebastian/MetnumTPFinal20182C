<?php

$folds = [2, 5, 10, 25];
//$ks = [1, 3, 5, 11, 31, 33, 35, 37, 39, 41, 43, 45, 47, 49, 51, 61, 71, 91, 101];
//$alfas = range(10,100,10);
//$fmins = range(0.05, 0.2, 0.01);
//$fmaxs = range(0.3, 1, 0.1);
//-----------------------------------------------------------------------
$ks = [35];
$alfas = [40];
$metodo = 1;
$fmins = [0.05];
$fmaxs = [0.4];

function calcularCasos($clasif, $real, &$res) {
    $c = fopen($clasif, 'r');
    $r = fopen($real, 'r');
    while ($csvC = fgetcsv($c)) {
        $csvR = fgetcsv($r);
        $res[$csvR[1]][$csvC[1]] ++;
    }
    fclose($c);
    fclose($r);
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

$salida = [];
$salida[] = ["metodo", "fold", "freqMin", "freqMax", "k", "alfa", "tiempo", "accuracy", "+precision", "+recall", "+f1score", "-precision", "-recall", "-f1score"];

foreach ($fmins as $min) {
    foreach ($fmaxs as $max) {
        foreach ($folds as $fold) {
            foreach ($ks as $k) {
                foreach ($alfas as $alfa) {
                    try {
                        $res = ['neg' => ['neg' => 0, 'pos' => 0,], 'pos' => ['neg' => 0, 'pos' => 0,],];
                        $dir = "resultados/folds/fmin-$min/fmax-$max/metodo-1/knn-$k/alfa-$alfa/$fold-fold";
                        echo $dir.PHP_EOL;
                        $tiempo = 0.0;
                        foreach (range(1, $fold) as $iter) {
                            $clasif = "$dir/clasif-$iter.csv";
                            $real = "$dir/real-$iter.csv";
                            $extra = "$dir/extra-$iter.txt";
                            if (!file_exists($extra)) {
                                throw new Exception("Saracatunga");
                            }
                            $matches = [];
                            preg_match("/tiempo: (?<tiempo>\d+(\.\d+)?)/", file_get_contents($extra), $matches);
                            $tiempo += (double) ($matches["tiempo"]);
                            calcularCasos($clasif, $real, $res);
                        }
                        $tiempo = $tiempo / $fold;
                        $neg_tn = $pos_tp = $res['pos']['pos'];
                        $neg_fn = $pos_fp = $res['neg']['pos'];
                        $neg_fp = $pos_fn = $res['pos']['neg'];
                        $neg_tp = $pos_tn = $res['neg']['neg'];

                        $pos_pres = precision($pos_tp, $pos_fn, $pos_fp, $pos_tn);
                        $pos_rec = recall($pos_tp, $pos_fn, $pos_fp, $pos_tn);
                        $pos_f1 = f1score($pos_tp, $pos_fn, $pos_fp, $pos_tn);

                        $neg_pres = precision($neg_tp, $neg_fn, $neg_fp, $neg_tn);
                        $neg_rec = recall($neg_tp, $neg_fn, $neg_fp, $neg_tn);
                        $neg_f1 = f1score($neg_tp, $neg_fn, $neg_fp, $neg_tn);

                        $acc = ($pos_tp + $pos_tn) / ($pos_tp + $pos_tn + $pos_fp + $pos_fn);
                        $salida[] = ["m$metodo", $fold, $min, $max, $k, $alfa, round($tiempo, 6), round($acc, 6), round($pos_pres,6), round($pos_rec,6), round($pos_f1,6), round($neg_pres,6), round($neg_rec,6), round($neg_f1,6)];
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }
    }
}

$archSal = fopen("resultados/agrupadosFolds.csv", "w");
foreach ($salida as $linea) {
    fputcsv($archSal, $linea);
}
fclose($archSal);
