<?php

function filtro($valores) {
    return function ($dato) use ($valores) {
        foreach ($valores as $key => $value) {
            if ($dato[$key] != $value) {
                return false;
            }
        }
        return true;
    };
}

function promediar($label, $array) {
    $count = count($array);
    $acum = [$label, 0, 0, 0, 0, 0, 0, 0, 0];
    foreach ($array as $linea) {
        for ($i = 6; $i <= 13; ++$i) {
            $acum[$i - 5] += $linea[$i] / $count;
            //echo  $linea[$i];
        }
    }
    return $acum;
}

$folds = [2, 5, 10, 25];
$ks = [1, 3, 5, 11, 31, 33, 35, 37, 39, 41, 43, 45, 47, 49, 51, 61, 71, 91, 101];
$alfas = range(10,100,10);

$archivo = fopen("resultados/agrupadosPca.csv", 'r');
$datos = [];
while ($datos[] = fgetcsv($archivo));
fclose($archivo);

$archivo = fopen("resultados/promediosAlfa.csv", "w");
fputcsv($archivo, ["alfa", "tiempo", "accuracy", "+precision", "+recall", "+f1score", "-precision", "-recall", "-f1score"]);
foreach ($alfas          as $alfa) {
    fputcsv($archivo, promediar($alfa, array_filter($datos, filtro([4 => 35, 5 => $alfa, 1 => "f25"]))));
}
fclose($archivo);

/*$archivo = fopen("resultados/promediosFold.csv","w");
	fputcsv($archivo, ["fold","tiempo","accuracy","+precision","+recall","+f1score","-precision","-recall","-f1score"]);
	foreach ($folds as $fold) {
		fputcsv($archivo,promediar("f$fold",array_filter($datos,filtro("f$fold",1))));
	}
	fclose($archivo);	*/
