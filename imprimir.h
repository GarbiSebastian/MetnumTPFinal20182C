#ifndef IMPRIMIR_H
#define IMPRIMIR_H
#include "typedefs.h"
#include <vector>
#include <iostream>
#include <iomanip>

using namespace std;

template<typename T>
void imprimir(vector< vector< T > > &m, ostream &out) {
	for (unsigned int i = 0; i < m.size(); i++) {
		vector< T > v = m[i];
		for (unsigned int j = 0; j < v.size(); j++) {
			out << v[j] << " ";
		}
		out << endl;
	}
}

template<typename T>
void imprimirUchar(vector< vector< T > > &m, ostream &out) {
	for (unsigned int i = 0; i < m.size(); i++) {
		vector< T > v = m[i];
		for (unsigned int j = 0; j < v.size(); j++) {
			out << (int) v[j] << " ";
		}
		out << endl;
	}
}

//
//template<typename T>
//void imprimir(vector< vector< T > > &m){
//	imprimir(m,cout);
//}

template<typename T>
void imprimir(vector<T> &v, ostream &out, bool transpuesto) {
	out << scientific;
	if (transpuesto) {
		for (unsigned int i = 0; i < v.size(); i++) {
			//			out << (double)v[i] << endl;
			out << v[i] << endl;
		}
	} else {
		//		out << (double)v[0];
		out << (int) v[0];
		for (unsigned int i = 1; i < v.size(); i++) {
			//			out << " " << (double)v[i];
			out << " " << v[i];
		}
		out << endl;
	}
}

template<typename T>
void imprimirUchar(vector<T> &v, ostream &out, bool transpuesto) {
	out << scientific;
	if (transpuesto) {
		for (unsigned int i = 0; i < v.size(); i++) {
			//			out << (double)v[i] << endl;
			out << (int) v[i] << endl;
		}
	} else {
		//		out << (double)v[0];
		out << (int) v[0];
		for (unsigned int i = 1; i < v.size(); i++) {
			//			out << " " << (double)v[i];
			out << " " << (int) v[i];
		}
		out << endl;
	}
}

template<typename T>
void imprimir(vector<T> &v, ostream &out) {
	imprimir(v, out, false);
}

template<typename T>
void imprimir(vector<T> &v) {
	imprimir(v, cout);
}
#endif /* IMPRIMIR_H */