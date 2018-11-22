<?php

$neg = fopen("datos/neg.csv", "r");
$pos = fopen("datos/pos.csv", "r");

$total = 25000;
$folds = [2, 5, 10, 25];
foreach ($folds as $fold) {
    $size = $total / $fold;
    $dir = "datos/fold/$fold-fold";
    exec("mkdir -p $dir");
    foreach (range(1, $fold) as $iteracion) {
        $salida = fopen("$dir/iter-$iteracion.csv", "w");
        for ($i = 0; $i < $total; $i++) {
            echo "$fold - $iteracion - $i" . PHP_EOL;
            $csvN = fgetcsv($neg);
            $csvP = fgetcsv($pos);
            if ($i >= (($iteracion - 1) * $size) && $i < $iteracion * $size) {
                $csvN[1] = 'test';
                $csvP[1] = 'test';
            } else {
                $csvN[1] = 'train';
                $csvP[1] = 'train';
            }
            fputcsv($salida, $csvN);
            fputcsv($salida, $csvP);
        }
        rewind($neg);
        rewind($pos);
    }
}

fclose($neg);
fclose($pos);
