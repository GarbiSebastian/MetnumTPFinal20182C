#ifndef METODOPOTENCIA_H
#define METODOPOTENCIA_H

#include "typedefs.h"
#include <math.h>
#include <assert.h>

using namespace std;

double metodoPotencia(matrizReal &B, vectorReal &v, int niter, double epsilon);
vectorReal randVector(unsigned int n);

#endif /* METODOPOTENCIA_H */