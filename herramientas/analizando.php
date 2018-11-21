<?php
	function filtro($valores){
		return function ($dato) use ($valores){
			foreach ($valores as $key => $value) {
				if($dato[$key] != $value){
					return false;
				}
			}
			return true;
		};
	}


	function promediar($label,$array){
		$count = count($array);
		$acum = [$label,0,0,0,0,0,0,0,0];
		foreach ($array as $linea) {
			for($i=6; $i<=13;++$i){
				$acum[$i-5] += $linea[$i]/$count;
				//echo  $linea[$i];
			}	
		}
		return $acum;
	}
	
	$folds = [2, 5, 10, 25];
	$ks = [1, 3, 5, 11, 31, 51 , 71 , 91, 101];

	$archivo = fopen("resultados/agrupados.csv",'r');
	$datos=[];
	while($datos[] = fgetcsv($archivo));
	fclose($archivo);

	$archivo = fopen("resultados/promediosK.csv","w");
	fputcsv($archivo, ["k","tiempo","accuracy","+precision","+recall","+f1score","-precision","-recall","-f1score"]);
	foreach ($ks as $k) {
		fputcsv($archivo,promediar($k,array_filter($datos,filtro([4=>$k,1=>"f25"]))));
	}
	fclose($archivo);

	/*$archivo = fopen("resultados/promediosFold.csv","w");
	fputcsv($archivo, ["fold","tiempo","accuracy","+precision","+recall","+f1score","-precision","-recall","-f1score"]);
	foreach ($folds as $fold) {
		fputcsv($archivo,promediar("f$fold",array_filter($datos,filtro("f$fold",1))));
	}
	fclose($archivo);	*/
