# -*- coding: utf-8 -*-
import fileinput
import numpy
import Gnuplot
from functools import reduce

variacion = "fold"
resto = []
folds= []
tiempos = []
tiemposPCA = []
tiemposkNN = []
accuracys= []
pos_precision= []
pos_recall = []
pos_f1score = []
neg_precision= []
neg_recall = []
neg_f1score = []
for line in fileinput.input():
    lexp = line.split(",")
    if lexp[0] != "metodo":
        folds.append(int(lexp[1]))
        tiempos.append(float(lexp[6]))
        tiemposPCA.append(float(lexp[7]))
        tiemposkNN.append(float(lexp[8]))
        accuracys.append(float(lexp[9]))
        pos_precision.append(float(lexp[10]))
        pos_recall.append(float(lexp[11]))
        pos_f1score.append(float(lexp[12]))
        neg_precision.append(float(lexp[13]))
        neg_recall.append(float(lexp[14]))
        neg_f1score.append(float(lexp[15]))
        #resto.append(lexp[9:])


print folds
print tiempos
print accuracys
print pos_precision
print pos_recall
print pos_f1score
print neg_precision
print neg_recall
print neg_f1score

a=folds
t = 50000
foldtams = [(f-1)*t /f for f in folds]

xticsList = ["'"+str((a[i]-1)*t/a[i])+"' "+str(i) for i in range(len(folds))]
#xticsList = map((lambda x : x.replace('.',',')),xticsList)
xtics=", ".join(xticsList)
maxAcc = max(accuracys)

g = Gnuplot.Gnuplot()
g.xlabel("Tamaño del train")
g("set key top left outside ")
g("set terminal png size 1000, 500")
g("set grid y")
#g("set style data histograms")
#g("set style histogram rowstacked")
g("set boxwidth 0.8")
g("set style fill solid 1 border -1")
#g("set xtics("+xtics+") scale 0")
#g("set xrange [-1:"+str(len(folds))+"]")
g("set ytics nomirror")


"""g("set y2range [0:]")
g("set y2label 'Tiempo (seg)'")
g("set y2tics 15")
g("set grid y2")"""

s = range(len(folds))
s = foldtams
d1 = Gnuplot.Data(s,pos_precision,using="1:2", title="Precisión +",with_="linespoint lw 2 ps 1.7 pt 5 lc rgb '#ff0000'")
d2 = Gnuplot.Data(s,neg_precision,using="1:2", title="Precisión -",with_="linespoint lw 2 ps 1.7 pt 7 lc rgb '#00ff00'")
d3 = Gnuplot.Data(s,pos_recall,using="1:2", title="Recall +",with_="linespoint lw 2 ps 1.3 pt 5 lc rgb '#770000'")
d4 = Gnuplot.Data(s,neg_recall,using="1:2", title="Recall -",with_="linespoint lw 2 ps 1.3 pt 7 lc rgb '#007700'")
d5 = Gnuplot.Data(s,pos_f1score,using="1:2", title="F1-score +",with_="linespoint lw 2 ps 1.5 pt 4 lc rgb '#aa0000'")
d6 = Gnuplot.Data(s,neg_f1score,using="1:2", title="F1-score -",with_="linespoint lw 2 ps 1.5 pt 6 lc rgb '#00aa00'")
d7 = Gnuplot.Data(s,accuracys,using="1:2",title="Accuracy",with_="linespoint lw 2 ps 1.5 pt 3")
d8 = Gnuplot.Data(s,tiempos,using="1:2", title="Tiempo total(seg)", with_="linespoints lw 2 ps 1.5 axes x1y2")
d9 = Gnuplot.Data(s,tiemposPCA,using="1:2", title="Tiempo PCA(seg)", with_="linespoints lw 2 ps 1.5 axes x1y2")
d10 = Gnuplot.Data(s,tiemposkNN,using="1:2", title="Tiempo kNN(seg)", with_="linespoints lw 2 ps 1.5 axes x1y2")

#PLOT ACCURACY
#g("set yrange [0.7:0.73]")
#g.ylabel("Accuracy")
g("set output 'accuracy-variacion-fold.png'")
g.title("Variación del tamaño del train")
g.plot(d7,d1,d5,d3,d2,d6,d4)

g.ylabel("Tiempo (seg)")
g("unset yrange")
#g("set yrange 7:]")
g("set output 'tiempoPCA-variacion-fold.png'")
g.plot(d9)

"""g("set style histogram cluster gap 1")#g("set style histogram rowstacked")

#PLOT F1SCORE
g("set yrange [:0.74]")
g.title("F1-score")
g.ylabel("F1-score")
#g("set yrange [0.5:0.8]")
g("set output 'f1score-variacion-fold.png'")
g.plot(d5,d6)
g("unset yrange")

#PLOT PRECISION
g.title("Precision")
g.ylabel("Precision")
#g("set yrange [0.5:0.8]")
#g("set ytics 0.05")
g("set output 'precision-variacion-fold.png'")
dl = Gnuplot.Data(folds,[0.625 for i in range(len(folds))],using=2,with_="lines lw 2",title="0,625" )
g.plot(d1,d2)
g("unset yrange")

#PLOT RECALL
g.title("Recall")
g.ylabel("Recall")
#g("set yrange [0.4:0.9]")
g("set output 'recall-variacion-fold.png'")
g.plot(d3,d4)
g("unset yrange")


d1 = Gnuplot.Data(folds,pos_precision,using="2", title="Precision")
d2 = Gnuplot.Data(folds,neg_precision,using="2", title="Precision")
d3 = Gnuplot.Data(folds,pos_recall,using="2", title="Recall" )
d4 = Gnuplot.Data(folds,neg_recall,using="2", title="Recall" )
d5 = Gnuplot.Data(folds,pos_f1score,using="2", title="F1-score" )
d6 = Gnuplot.Data(folds,neg_f1score,using="2", title="F1-score" )

#POSITIVAS
g.title("Reviews Positivos")
g.ylabel("Métricas")
#g("set yrange [0.4:0.8]")
g("set output 'metricas-pos-variacion-fold.png'")
g.plot(d1,d3,d5)
g("unset yrange")

#NEGATIVAS
g.title("Reviews Negativos")
g.ylabel("Métricas")
#g("set yrange [0.5:0.9]")
g("set output 'metricas-neg-variacion-fold.png'")
g.plot(d2,d4,d6)
g("unset yrange")


del g

g = Gnuplot.Gnuplot()
g.xlabel("# componentes")
g("set key top left")
g("set terminal png size 1000, 500")
g("set grid y")
#g("set style data histograms")
#g("set style histogram rowstacked")
#g("set boxwidth 0.8")
g("set style fill solid 1 border -1")
g("set xtics("+xtics+") scale 0")
g("set xrange [-1:"+str(len(folds))+"]")
g.title("Tiempo con respecto a la cantidad de componentes")
g.ylabel("Tiempo (seg)")
g("set output 'tiempo-variacion-fold.png")
d1 = Gnuplot.Data(folds,tiempos,using=2, title="Tiempo",with_="linespoint")
g.plot(d1)"""