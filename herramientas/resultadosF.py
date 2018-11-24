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
        fmins.append(float(lexp[0])) 
        fmaxs.append(float(lexp[1]))
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
size = len(accuracys)
xtics=", ".join(["'"+str(tams[i])+"' "+str(i) for i in range(size)])

g = Gnuplot.Gnuplot()
g.xlabel("#palabras aceptadas")
g("set key top left")
g("set terminal png size 1000, 500")
g("set grid y")
g("set grid x")
d7 = Gnuplot.Data(tams,tiempos,using="1:2",title="Tiempo (seg)",with_="points")
g.ylabel("Tiempo (seg)")
g("set output 'tiempo-variacion-tam.png'")
g.title("Tiempo con respecto al tamaño del vocabulario")
g.plot(d7)
del g

def igualFloat(valor,igual):
    return igual-0.001 < valor and  valor < igual+0.001
fminsSinDup = list(set(fmins))
size2 = len(fminsSinDup)

a = [ round(float(i)/100,2) for i in range(5,21,1)]
b03 = [ round(accuracys[i],6) for i in range(size) if igualFloat(fmaxs[i],0.3)]
b04 = [ round(accuracys[i],6) for i in range(size) if igualFloat(fmaxs[i],0.4)]
b05 = [ round(accuracys[i],6) for i in range(size) if igualFloat(fmaxs[i],0.5)]
b06 = [ round(accuracys[i],6) for i in range(size) if igualFloat(fmaxs[i],0.6)]
b07 = [ round(accuracys[i],6) for i in range(size) if igualFloat(fmaxs[i],0.7)]
b08 = [ round(accuracys[i],6) for i in range(size) if igualFloat(fmaxs[i],0.8)]
b09 = [ round(accuracys[i],6) for i in range(size) if igualFloat(fmaxs[i],0.9)]
b1  = [ round(accuracys[i],6) for i in range(size) if igualFloat(fmaxs[i],1)]
xtics=", ".join(["'"+str(a[i]).replace(".",",")+"' "+str(i) for i in range(len(a))])
#print a;

g = Gnuplot.Gnuplot()
g.xlabel("freq min ")
g("set key top right")
g("set terminal png size 1000, 500")
g("set style data histograms")
g("set style histogram cluster gap 1")#g("set style histogram rowstacked")
g("set boxwidth 1")
g("set style fill solid 1 border 0")
g("set xtics("+xtics+") scale 0")
g("set xrange [-1:"+str(size2)+"]")
g("set grid y")
#g("set grid x")
d1 = Gnuplot.Data(a, b03, using="2",title="frec. max=0,3")
d2 = Gnuplot.Data(a, b04, using="2",title="frec. max=0,4")
d3 = Gnuplot.Data(a, b05, using="2",title="frec. max=0,5")
d4 = Gnuplot.Data(a, b06, using="2",title="frec. max=0,6")
d5 = Gnuplot.Data(a, b07, using="2",title="frec. max=0,7")
d6 = Gnuplot.Data(a, b08, using="2",title="frec. max=0,8")
d7 = Gnuplot.Data(a, b09, using="2",title="frec. max=0,9")
d8 = Gnuplot.Data(a, b1,  using="2",title="frec. max=1,0")
g.ylabel("Accuracy")
g("set output 'accuracy-variacion-frango.png'")
g.title("Accuracy con respecto al rango de frecuencia de palabras")
g.plot(d1,d2,d3,d4,d5,d6,d7,d8)
del g