# -*- coding: utf-8 -*-
import fileinput
import numpy
import Gnuplot
from functools import reduce

resto = []
ks = []
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
    if lexp[0] != "k":
        ks.append(int(lexp[0])) 
        tiempos.append(float(lexp[1]))
        accuracys.append(float(lexp[2]))
        pos_precision.append(float(lexp[3]))
        pos_recall.append(float(lexp[4]))
        pos_f1score.append(float(lexp[5]))
        neg_precision.append(float(lexp[6]))
        neg_recall.append(float(lexp[7]))
        neg_f1score.append(float(lexp[8]))
        resto.append(lexp[9:])

xtics=", ".join(["'"+str(ks[i])+"' "+str(i) for i in range(len(ks))])        

#PLOT ACCURACY
g = Gnuplot.Gnuplot()
g.title("Evolución de accuracy ante variación de la cantidad de vecinos")
g.xlabel("k vecinos")
g.ylabel("Accuracy")
g("set terminal png size 1000, 500")
g("unset key ")
g("set grid y")
g("set style data histograms")
g("set style histogram rowstacked")
g("set boxwidth 0.8")
g("set style fill solid 1.0 border -1")
g("set ytics 0.01 nomirror")
g("set yrange [0.65:0.68]")
g("set xtics("+xtics+")")
g("set xrange [-1:"+str(len(ks))+"]")
g("xequiv=100")
d1 = Gnuplot.Data(ks,accuracys,using="2",title="Accuracy")
d2 = Gnuplot.Data(range(len(ks)),[ accuracys[6] for i in range(len(ks))],using="2",title="liñita",with_="lines lt 2 lw 2")
g("set output 'accuracy-variacion-k.png'")
g.plot(d1,d2)
del g

#PLOT PRECISION
g = Gnuplot.Gnuplot()
g.xlabel("k vecinos")
g("set terminal png size 1000, 500")
g("set key top right")#g("unset key ")
g("set grid y")
g("set style data histograms")
g("set style histogram cluster gap 1")#g("set style histogram rowstacked")
g("set boxwidth 0.9")
g("set style fill solid 1 border -1")
g("set ytics 0.1 nomirror")
g("set yrange [0:1]")
g("set xtics("+xtics+") scale 0")
g("set xrange [-1:"+str(len(ks))+"]")
d1 = Gnuplot.Data(ks,pos_precision,using="2", title="Reviews Positivos")
d2 = Gnuplot.Data(ks,neg_precision,using="2", title="Reviews Negativos")
d3 = Gnuplot.Data(ks,pos_recall,using="2", title="Reviews Positivos" )
d4 = Gnuplot.Data(ks,neg_recall,using="2", title="Reviews Negativos" )
d5 = Gnuplot.Data(ks,pos_f1score,using="2", title="Reviews Positivos" )
d6 = Gnuplot.Data(ks,neg_f1score,using="2", title="Reviews Negativos" )

g.title("Precision")
g.ylabel("Precision")
g("set output 'precision-variacion-k.png'")
g.plot(d1,d2)

g.title("Recall")
g.ylabel("Recall")
g("set output 'recall-variacion-k.png'")
g.plot(d3,d4)

g.title("F1-score")
g.ylabel("F1-score")
g("set output 'f1score-variacion-k.png'")
g.plot(d5,d6)

d1 = Gnuplot.Data(ks,pos_precision,using="2", title="Precision")
d2 = Gnuplot.Data(ks,neg_precision,using="2", title="Precision")
d3 = Gnuplot.Data(ks,pos_recall,using="2", title="Recall" )
d4 = Gnuplot.Data(ks,neg_recall,using="2", title="Recall" )
d5 = Gnuplot.Data(ks,pos_f1score,using="2", title="F1-score" )
d6 = Gnuplot.Data(ks,neg_f1score,using="2", title="F1-score" )

g.title("Reviews Positivos")
g.ylabel("Métricas")
g("set output 'metricas-pos-variacion-k.png'")
g.plot(d1,d3,d5)

g.title("Reviews Negativos")
g.ylabel("Métricas")
g("set output 'metricas-neg-variacion-k.png'")
g.plot(d2,d4,d6)


del g

"""g = Gnuplot.Gnuplot()
g.title("Evolución de precision ante variación de la cantidad de vecinos")
g.xlabel("k vecinos")
g.ylabel("Presicion")
g("set terminal png size 1000, 500")
g("unset key ")
g("set grid y")
g("set style data histograms")
g("set style histogram rowstacked")
g("set boxwidth 0.8")
g("set style fill solid 1.0 border -1")
g("set ytics 0.01 nomirror")
g("set yrange [0.65:0.68]")
xtics=", ".join(["'"+str(ks[i])+"' "+str(i) for i in range(len(ks))])
g("set xtics("+xtics+")")
g("set xrange [-1:"+str(len(ks))+"]")
g("xequiv=100")
d1 = Gnuplot.Data(ks,accuracys,using="2",title="Accuracy")
d2 = Gnuplot.Data(range(len(ks)),[ accuracys[6] for i in range(len(ks))],using="2",title="liñita",with_="lines lt 2 lw 2")
g("set output 'accuracy-variacion-k.png'")
g.plot(d1,d2)
del g
"""
# precision: tp/ tp+fp

"""f=(lambda a, b: a + b)
precision=reduce(f,precisiones)/sujetos
recall=reduce(f,recalles)/sujetos
accuracy=100*float(aciertos)/total

#SALIDAS
plotPrecision()
plotRecall()
plotAccuracy()
print "precision: "+str(precision)
print "recall: "+str(recall)
print "accuracy: "+str(accuracy)
"""