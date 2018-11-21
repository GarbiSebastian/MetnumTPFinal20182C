# -*- coding: utf-8 -*-
import fileinput
import numpy
import Gnuplot
from functools import reduce

"""def datos(i,tipo=TP):
    return resultados[i][tipo]"""
#print resultados

"""for line in fileinput.input():
    total+=1
    item = line.split()
    real = int(item[0])-1
    obtenido = int(item[1])-1
    confusion[real][obtenido]+=1
    x_sujetos[real]+=1
    positivos[obtenido]+=1
    if real==obtenido:
        resultados[real][TP]+=1
        aciertos+=1
    else:
        resultados[real][FN]+=1
        resultados[obtenido][FP]+=1

for i in range(sujetos):
    resultados[i][TN]=total - int(total/sujetos) - resultados[i][FP]"""
for line in fileinput.input():
    item = line.split(",")
    print item



def gBars(sujetos,xlabel="Sujetos",ylabel="Resultados",title="Resultados"):
    g = Gnuplot.Gnuplot()
    g.title(title)
    g.xlabel(xlabel)
    g.ylabel(ylabel)
    g("set terminal png size 1000, 500")
    g("unset key ")
    g("set grid y")
    g("set style data histograms")
    g("set style histogram rowstacked")
    g("set boxwidth 0.8")
    g("set style fill solid 1.0 border -1")
    g("set ytics 10 nomirror")
    #g("set yrange [0:100]")
    g("set ytics 10")
    g("set xtics 1")
    xtics=", ".join(["'"+str(i+1)+"' "+str(i) for i in range(sujetos)])
    g("set xtics("+xtics+")")
    g("set xrange [-1:"+str(sujetos)+"]")
    g("xequiv=100")
    return g

def auxDiv(i):
    if positivos[i]>0:
       return (float(datos(i,tipo=TP))/positivos[i])*100
    else:
       return 0

"""x=range(sujetos)
recalles=[ (float(datos(i,tipo=TP))/x_sujetos[i])*100 for i in x ]
precisiones=[ auxDiv(i) for i in x ]
accuracys=[ (float(datos(i,tipo=TP)+datos(i,tipo=TN))/total)*100 for i in x ]"""


def plotRecall():
    g = gBars(sujetos,title="Recall")
    d1 = Gnuplot.Data(x,recalles,using="2",title="Recall")
    g("set output 'recall.png'")
    g.plot(d1)
    del g

def plotPrecision():
    g = gBars(sujetos,title="Precisión")
    d1 = Gnuplot.Data(x,precisiones,using="2",title="Precision")
    g("set output 'precision.png'")
    g.plot(d1)
    del g

def plotAccuracy():
    g = gBars(sujetos,title="Accuracy")
    d1 = Gnuplot.Data(x,accuracys,using="2",title="Accuracy")
    g("set output 'accuracy.png'")
    g.plot(d1)
    del g



def mediaArmonica():
    print 1

def auxDiv(i):
    if positivos[i]>0:
       return (float(datos(i,tipo=TP))/positivos[i])*100
    else:
       return 0


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