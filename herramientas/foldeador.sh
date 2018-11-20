#!/bin/bash
tokenizado="datos/imdb_tokenized.csv";
reordenado="datos/reordenado.csv";
cat $tokenizado | grep pos > datos/pos.csv; #separo los reviews positivos 
cat $tokenizado | grep neg > datos/neg.csv; #separo los reviews negativos
paste -d '\n' datos/neg.csv datos/pos.csv > $reordenado; #intercalo los reviews neg y pos
total=50000;
rm -rf datos/fold; #limpio el directiorio
for k_fold in 2 5 10 25; do #para cada fold
	for iteracion in $(seq -s' ' 1 $k_fold); do #para cada iteracion del fold 
		fold_dir="datos/fold/$k_fold-fold"; #directorio destino
        tam=$(($total/$k_fold)); #tamaño del fold
		mkdir -p $fold_dir; #
		archivo="$fold_dir/iter-$iteracion.csv"; #archivo de iteracion
		inicio=$(( ($iteracion-1)*$tam )); 
	    fin=$(( $iteracion*$tam ));
	    resto=$(( $total-$fin ));
        echo "Armando $archivo";
	    head -n $inicio $reordenado | sed -e 's/test/train/g' >> $archivo; # todo lo que sea menor que inicio va a train
	    head -n $fin $reordenado | tail -n $tam | sed -e 's/train/test/g' >> $archivo; # todo lo que esté en rango va a test
	    tail -n $resto $reordenado | sed -e 's/test/train/g' >> $archivo; # todo lo que esté por encima de max va a train
	done;
done;
