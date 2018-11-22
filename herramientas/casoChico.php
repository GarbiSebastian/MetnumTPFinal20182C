<?php

$indice = 0;
$datasets = ['test', 'train'];
$clases = ['neg', 'pos'];
$cantVocabulario = 100;
$cantidad = 100;
$cantTokensMin = 50;
$cantTokensMax = 200;

foreach ($datasets as $dataset) {
    foreach ($clases as $clase) {
        for ($index = 0; $index < $cantidad; $index++) {
            $cantTokens = rand($cantTokensMin, $cantTokensMax);
            $tokens = [];
            for ($j = 0; $j < $cantTokens; $j++) {
                $tokens[] = rand(1, $cantVocabulario);
            }
            echo "$indice,$dataset,$clase," . implode(",", $tokens) . PHP_EOL;
            $indice++;
        }
    }
}
