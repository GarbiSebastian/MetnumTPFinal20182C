#include <cstdlib>
#include <cmath>
#include "metodoPotencia.h"
#include "funciones.h"
#include <iostream>
#include <iomanip>

double metodoPotencia(matrizReal &B, vectorReal &v, int niter, double epsilon) {
	v.clear();
	vectorReal tmp(B.size(), 0);
	//v <- x0
	v = randVector(B.size());
	//Para i = 1, . . . , niter
	for (int i = 0; i < niter; i++) {
		tmp = A_por_v(B, v);
		normalizar(tmp);
		if (norma2(resta(v, tmp)) <= epsilon) {
			break;
		} //si no cambio al menos epsilon en norma 2
		//v <- Bv/||Bv||
		v = tmp;
	}
	//lambda <- v'*Bv/(v'*v) //v siempre tiene norma uno entonces norma Cuadrada de v es 1

	double normaCuadrada = pow(norma2(v), 2);
	vectorReal Bv = A_por_v(B, v);
	//Devolver lambda v .
	return productoInterno(v, Bv) / normaCuadrada;
}

vectorReal randVector(unsigned int n) {
	vectorReal r(n, 0.0);
	srand(1);
	for (unsigned int i = 0; i < n; i++) {
		r[i] = (rand() % 1000) / 1000.0;
	}
	return r;
}

