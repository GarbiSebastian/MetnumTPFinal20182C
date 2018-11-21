<?php

$folds = [2, 5, 10, 25];
$ks = [1, 3, 5, 11, 31, 33, 35, 37, 39, 41, 43, 45, 47, 49, 51, 61, 71, 91, 101];

function calcularCasos($clasif,$real,&$res){
	$c = fopen($clasif,'r');
	$r = fopen($real,'r');
	while($csvC= fgetcsv($c)){
		$csvR = fgetcsv($r);
		$res[$csvR[1]][$csvC[1]]++;
	}
	fclose($c);
	fclose($r);
}

function precision($tp,$fn,$fp,$tn){return $tp/($tp+$fp);}

function recall($tp,$fn,$fp,$tn){return $tp/($tp+$fn);}

function f1score($tp,$fn,$fp,$tn){
	$p = precision($tp,$fn,$fp,$tn);
	$r = recall($tp,$fn,$fp,$tn);
	return 2*$p*$r/($p+$r);
}

$salida = [];
$salida[] = ["metodo","fold","freqMin","freqMax","k","alfa","tiempo","accuracy","+precision","+recall","+f1score","-precision","-recall","-f1score"];

foreach($folds as $fold){
	foreach($ks as $k){
		$res = ['neg' => ['neg' => 0,'pos' => 0,],'pos' => ['neg' => 0,'pos' => 0,],];
		$dir = "resultados/metodo-0/knn-$k/$fold-fold";
		$tiempo = 0.0;
		try{
			foreach (range(1,$fold) as $iter) {
					$clasif = "$dir/clasif-$iter.csv";
					$real   = "$dir/real-$iter.csv";
					$extra  = "$dir/extra-$iter.txt";
					if(!file_exists($extra)){
						throw new Exception("Saracatunga");
					}
					$matches=[];
					preg_match("/tiempo: (?<tiempo>\d+(\.\d+)?)/", file_get_contents($extra), $matches);
					$tiempo += (double)($matches["tiempo"]);
					calcularCasos($clasif,$real,$res);
			}
			$tiempo = $tiempo/$fold;
			$neg_tn = $pos_tp = $res['pos']['pos'];
			$neg_fn = $pos_fp = $res['neg']['pos'];
			$neg_fp = $pos_fn = $res['pos']['neg'];
			$neg_tp = $pos_tn = $res['neg']['neg'];
			
			$pos_pres = precision($pos_tp,$pos_fn,$pos_fp,$pos_tn);
			$pos_rec  = recall($pos_tp,$pos_fn,$pos_fp,$pos_tn);
			$pos_f1   = f1score($pos_tp,$pos_fn,$pos_fp,$pos_tn);	

			$neg_pres = precision($neg_tp,$neg_fn,$neg_fp,$neg_tn);
			$neg_rec  = recall($neg_tp,$neg_fn,$neg_fp,$neg_tn);
			$neg_f1   = f1score($neg_tp,$neg_fn,$neg_fp,$neg_tn);

			$acc 	  = ($pos_tp+$pos_tn)/($pos_tp+$pos_tn+$pos_fp+$pos_fn);
			$salida[] = ["m0","f$fold",0.1,0.5,$k,50,$tiempo,$acc,$pos_pres,$pos_rec,$pos_f1,$neg_pres,$neg_rec,$neg_f1];
		}catch(Exception $e){}
	}
}

$archSal = fopen("resultados/agrupados.csv", "w+");
foreach ($salida as $linea) {
	fputcsv($archSal, $linea);
}
fclose($archSal);