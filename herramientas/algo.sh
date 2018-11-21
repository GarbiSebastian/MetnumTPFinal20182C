#!/bin/bash
archivosResultado="clasif-*.csv";
archivosReal="real-*.csv";
salidaResultado="clasif.csv";
salidaReal="real.csv";
for k in 1 3 5 11 31 51; do 
	for k_fold in 2 5 10 25; do #para cada fold
		dir_resultados="resultados/metodo-0/knn-$k/$k_fold-fold"; #directorio destino
		cat $dir_resultados/$archivosResultado > $dir_resultados/$salidaResultado;
		cat $dir_resultados/$archivosReal      > $dir_resultados/$salidaReal;
		diff -y $dir_resultados/$salidaResultado $dir_resultados/$salidaReal
	done;
done;
