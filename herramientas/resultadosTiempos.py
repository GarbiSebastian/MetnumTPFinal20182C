# -*- coding: utf-8 -*-
import fileinput
import numpy
import Gnuplot
from functools import reduce

metodos = []
folds = []
fmins = []
fmaxs = []
ks = []
alfas = []
tiempos = []
for line in fileinput.input():
    lexp = line.split(",")
    if lexp[0] != "metodo":
        metodos.append(lexp[0])
        folds.append(lexp[1])
        fmins.append(float(lexp[2]))
        fmaxs.append(float(lexp[3]))
        ks.append(int(lexp[4]))
        alfas.append(int(lexp[5]))
        tiempos.append(float(lexp[6]))

xtics=", ".join(["'"+str(ks[i])+"' "+str(i) for i in range(len(ks))])

g = Gnuplot.Gnuplot()
g.xlabel("k vecinos")
g("set key top left")
g("set terminal png size 1000, 500")
g("set grid y")
#g("set style data histograms")
#g("set style histogram rowstacked")
#g("set boxwidth 0.8")
g("set style fill solid 1 border -1")
g("set xtics("+xtics+") scale 0")
g("set xrange [-1:"+str(len(ks))+"]")
g.title("Tiempo con respecto a la cantidad de vecinos")
g.ylabel("Tiempo (seg)")
g("set output 'tiempo-variacion-k.png")
d1 = Gnuplot.Data(ks,tiempos,using=2, title="Tiempo",with_="linespoint")
g.plot(d1)