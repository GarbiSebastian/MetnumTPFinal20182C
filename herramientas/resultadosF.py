# -*- coding: utf-8 -*-
import fileinput
import numpy
import Gnuplot
from functools import reduce

resto = []
fmins= []
fmaxs=[]
tams = []
tiempos = []
accuracys= []
pos_precision= []
pos_recall = []
pos_f1score = []
neg_precision= []
neg_recall = []
neg_f1score = []
for line in fileinput.input():
    lexp = line.split(",")
    if lexp[0] != "fqmin":
        fmins.append(lexp[0]) 
        fmaxs.append(lexp[1]) 
        tams.append(int(lexp[2]))
        tiempos.append(float(lexp[3]))
        accuracys.append(float(lexp[4]))
        pos_precision.append(float(lexp[5]))
        pos_recall.append(float(lexp[6]))
        pos_f1score.append(float(lexp[7]))
        neg_precision.append(float(lexp[8]))
        neg_recall.append(float(lexp[9]))
        neg_f1score.append(float(lexp[10]))
        resto.append(lexp[11:])

print tams
print tiempos
xtics=", ".join(["'"+str(tams[i])+"' "+str(i) for i in range(len(tams))])
maxAcc = max(accuracys)

g = Gnuplot.Gnuplot()
g.xlabel("#palabras aceptadas")
g("set key top left")
g("set terminal png size 1000, 500")
g("set grid y")
g("set grid x")
#g("set style data histograms")
#g("set style histogram rowstacked")
#g("set boxwidth 0.8")
#g("set style fill solid 1 border -1")
#g("set xtics("+xtics+") scale 0")
#g("set xrange [-1:"+str(len(tams))+"]")

#g("set ytics 0.01 nomirror")
#g("set yrange [0.65:0.68]")

#g("xequiv=100")
d7 = Gnuplot.Data(tams,tiempos,using="1:2",title="Tiempo",with_="linespoints")
#d8 = Gnuplot.Data(range(len(tams)),[ maxAcc for i in range(len(tams))],using="2",title=str(round(maxAcc,6)).replace('.',','),with_="lines lt 2 lw 2")

#PLOT ACCURACY
#g("set yrange [:0.7]")
g.ylabel("Tiempo(s)")
g("set output 'tiempo-variacion-tam.png'")
g.title("Tiempo con respecto al tamaño del vocabulario")
g.plot(d7)

"""g("set style histogram cluster gap 1")#g("set style histogram rowstacked")

#PLOT F1SCORE
g("set yrange [:0.74]")
g.title("F1-score")
g.ylabel("F1-score")
#g("set yrange [0.5:0.8]")
g("set output 'f1score-variacion-alfa.png'")
g.plot(d5,d6)
g("unset yrange")

#PLOT PRECISION
g.title("Precision")
g.ylabel("Precision")
#g("set yrange [0.5:0.8]")
#g("set ytics 0.05")
g("set output 'precision-variacion-alfa.png'")
dl = Gnuplot.Data(tams,[0.625 for i in range(len(tams))],using=2,with_="lines lw 2",title="0,625" )
g.plot(d1,d2)
g("unset yrange")

#PLOT RECALL
g.title("Recall")
g.ylabel("Recall")
#g("set yrange [0.4:0.9]")
g("set output 'recall-variacion-alfa.png'")
g.plot(d3,d4)
g("unset yrange")


d1 = Gnuplot.Data(tams,pos_precision,using="2", title="Precision")
d2 = Gnuplot.Data(tams,neg_precision,using="2", title="Precision")
d3 = Gnuplot.Data(tams,pos_recall,using="2", title="Recall" )
d4 = Gnuplot.Data(tams,neg_recall,using="2", title="Recall" )
d5 = Gnuplot.Data(tams,pos_f1score,using="2", title="F1-score" )
d6 = Gnuplot.Data(tams,neg_f1score,using="2", title="F1-score" )

#POSITIVAS
g.title("Reviews Positivos")
g.ylabel("Métricas")
#g("set yrange [0.4:0.8]")
g("set output 'metricas-pos-variacion-alfa.png'")
g.plot(d1,d3,d5)
g("unset yrange")

#NEGATIVAS
g.title("Reviews Negativos")
g.ylabel("Métricas")
#g("set yrange [0.5:0.9]")
g("set output 'metricas-neg-variacion-alfa.png'")
g.plot(d2,d4,d6)
g("unset yrange")

"""
del g