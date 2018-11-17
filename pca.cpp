#include <cassert>

#include "pca.h"
#include "constantes.h"

void A_menos_vvt(matrizReal &A, vectorReal &v, double a) {
    unsigned int m = A.size();
    assert(m > 0 && m == v.size());
    assert(m == A[0].size());
    for (unsigned int i = 0; i < m; i++) {
        for (unsigned int j = 0; j < m; j++) {
            A[i][j] = A[i][j] - a * (v[i] * v[j]);
        }
    }
}

void matrizCovarianzas(matrizReal &entrada, matrizReal& cov, const vectorReal& medias) {
    unsigned int m = entrada.size();
    assert(m > 0);
    unsigned int n = entrada[0].size();
    assert(n == cov.size() && n == cov[0].size());
    //matrizReal B(m, vectorReal(n, 0));
    centrarRespectoA(entrada, medias, m);
    multiplicarPorTranspuesta(entrada, cov);
}

void matrizCovarianzasInversa(matrizReal &entrada, matrizReal& cov, const vectorReal& medias) {
    unsigned int m = entrada.size();
    assert(m > 0);
    unsigned int n = entrada[0].size();
    assert(m == cov.size() && m == cov[0].size());
    centrarRespectoA(entrada, medias, m);
    multiplicarPorTranspuestaInversa(entrada, cov);
}

matrizReal obtenerAlfaVectores(matrizReal &A, unsigned int alfa) {
    matrizReal res;
    vectorReal v;
    assert(alfa <= A.size());
    matrizReal B = A;
    double autovalor;
    int iteraciones = 1000;
    //double epsilon = 1 / pow(10, 12);
    for (unsigned int i = 0; i < alfa; i++) {
        //		cout << "*************" << endl;
        //		for (unsigned int i = 0; i < A.size(); i++) {
        //			imprimir(B[i], cout);
        //		}
        //		cout << "-------------" << endl;

        autovalor = metodoPotencia(B, v, iteraciones, epsilon);
        res.push_back(v);
        A_menos_vvt(B, v, autovalor);
    }
    return res;
}

void obtenerAlfaVectores(matrizReal &A, unsigned int alfa, matrizReal& res) {
    assert(alfa <= A.size());
    vectorReal v;
    int iteraciones = 1000;
    //double epsilon = 1 / pow(10, 12);
    double autovalor;
    for (unsigned int i = 0; i < alfa; i++) {
        autovalor = metodoPotencia(A, v, iteraciones, epsilon);
        res.push_back(v);
        A_menos_vvt(A, v, autovalor);
    }
}

void obtenerVtDesdeVt2(matrizReal& Vt2, matrizReal& Vt, matrizReal& X) {
    unsigned int m = X.size();
    unsigned int n = X[0].size();
    matrizReal Xt(n,vectorReal(m,0));
    transponer(X,Xt);
    for (unsigned int i = 0; i < X.size(); i++) {
        Vt.push_back(A_por_v(Vt2,Xt[i]));
    }
    
}