#include <map>

#include "knn.h"
#include "funciones.h"

using namespace std;

template<typename T>
real dameDistancia(vector<T>& u, vector<T> & v) {
    return norma2(resta(u, v));
}

void insertame(unsigned int indice, real distancia, vectorEntero &indices, vectorReal& distancias) {
    unsigned int k_vecinos = indices.size();
    int j = k_vecinos - 1;
    if (distancia < distancias[j] || indices[j] == -1) {
        indices[j] = indice;
        distancias[j] = distancia;
        while (j > 0 && (distancias[j - 1] > distancias[j] || indices[j - 1] == -1)) {
            distancia = distancias[j];
            indice = indices[j];
            distancias[j] = distancias[j - 1];
            indices[j] = indices[j - 1];
            distancias[j - 1] = distancia;
            indices[j - 1] = indice;
            j--;
        }
    }
}

template<typename T> void buscarAux(int k_vecinos, vector< vector<T> > & train, vector<T> & imagen, vectorEntero & indices, vectorReal &distancias) {
    indices = vectorEntero(k_vecinos, -1);
    distancias = vectorReal(k_vecinos, -1);

    //busco vecinos mas cercanos
    for (unsigned int i = 0; i < train.size(); i++) {
        real distancia = dameDistancia(imagen, train[i]);
        insertame(i, distancia, indices, distancias);
    }
}

void buscar(int k_vecinos, matrizReal & train, vectorReal & imagen, vectorEntero & indices, vectorReal &distancias) {
    buscarAux(k_vecinos,train,imagen,indices,distancias);
}

void buscar(int k_vecinos, matrizUchar & train, vectorUchar & imagen, vectorEntero & indices, vectorReal &distancias) {
    buscarAux(k_vecinos,train,imagen,indices,distancias);
}

template<typename T>
T votar_aux(unsigned int cant_categorias, const vector<T> & clases, const vectorEntero & indices, const vectorReal &distancias){
    map<T, int> bucket;
    map<T, double> bucketDistancias;
    for (unsigned int i = 0; i < indices.size(); i++) {
        int ind = indices[i];
        double dist = distancias[i];
        T clase = clases[ind];
        bucket[ clase ]++;
        bucketDistancias[ clase ] += dist;
    }
    T mejor;
    int max= -1; 
    for(auto it_clases = bucket.begin(); it_clases != bucket.end(); ++it_clases){
        if(it_clases->second > max){
            mejor = it_clases->first;
            max = it_clases->second;
        }
    }
    return mejor;
}

bool votar(unsigned int cant_categorias, const vector<bool>& vectorClases, const vectorEntero& indices, const vectorReal& distancias){
    return votar_aux(cant_categorias,vectorClases,indices,distancias);
}


//int votar(unsigned int cant_categorias, vectorEntero & labels, vectorEntero & indices, vectorReal &distancias) {
//    vectorEntero bucket(cant_categorias, 0);
//    vectorReal bucketDistacia(cant_categorias,0);
//    for (unsigned int i = 0; i < indices.size(); i++) {
//        bucket[ labels[indices[i]]-1 ]++;
//        bucketDistacia[ labels[indices[i]]-1 ] += distancias[i];
//    }
////    vectorReal bucket(cant_categorias, 0);
////    for (unsigned int i = 0; i < indices.size(); i++) {
////        bucket[ labels[indices[i]]-1 ]+=1/distancias[i];
////    }
//    unsigned int j = 0;
//    int max = 0;
//    for (unsigned int i = 0; i < cant_categorias; i++) {
//        if (bucket[i] > max) {
//            max = bucket[i];
//            j = i;
//        }else if(bucket[i] == max && bucketDistacia[i] < bucketDistacia[j]) {
//            j = i;
//        }
//    }
//    return j+1;
//}
