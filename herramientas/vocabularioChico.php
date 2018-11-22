<?php

$header = "word,count,num_documents,document_frequency,code" . PHP_EOL;
$linea = "sarasa,0,0";
$codigo = 1;
$vocabulario = 100;
echo $header;
for ($index = 0; $index < $vocabulario; $index++) {
    $freq = rand(1, 100) / 100;
    echo "$linea,$freq,$codigo" . PHP_EOL;
    $codigo++;
}
    