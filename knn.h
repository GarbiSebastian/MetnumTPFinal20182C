#ifndef KNN_H
#define KNN_H

#include "typedefs.h"
#include <math.h>

using namespace std;

void buscar(int k_vecinos, matrizReal & train, vectorReal & test, vectorEntero & indices , vectorReal &distancias);
//int votar(unsigned int cant_categorias, vectorEntero & labels, vectorEntero & indices , vectorReal &distancias);
bool votar(unsigned int cant_categorias, const vector<bool> & vectorClases, const vectorEntero & indices , const vectorReal &distancias);

#endif /* KNN_H */