/* 
 * File:   main.cpp
 * Author: sebastian
 *
 * Created on 12 de noviembre de 2018, 09:46
 */

#include <cstdlib>
#include "src/vector_builder.h"
#include "typedefs.h"
//#include "imprimir.h"
#include "funciones.h" 
#include "knn.h"
#include "pca.h"
#include <iostream>
#include <fstream>



using namespace std;

//requeridos
const string paramMetodo = "-m";
const string paramDatasetPath = "-d";
const string paramClasificacion = "-o";
//opcionales
const string paramVocabPath = "-v";
const string paramClasificacionReal = "-r";
const string paramDatosExtra = "-x";
const string paramFrecuencyMin = "-fi";
const string paramFrecuencyMax = "-fa";
const string paramK = "-k";
const string paramAlfa = "-a";

const int clases = 2; // pos neg

//Parametros variables
int metodo = -1;
string datasetPath = "";
string vocab_path = "datos/vocab.csv";
string archivoclasificacion = "clasif.csv";
string archivoclasificacionReal = "clasifReal.csv";
string archivoDatosExtra = "extradata.txt";
double frecuenciaMinima = 0.1;
double frecuenciaMaxima = 0.5;
int k_vecinos = 3;
int alfa = 50;
int bow_size = 0;


VectorizedEntriesMap train_entries;
VectorizedEntriesMap test_entries;

void procesarVariables(int argc, char** argv) {
    bool metodoOk = false,
            datasetPathOk = false,
            clasifOk = false;
    for (int i = 1; i < argc; i += 2) {
        string val = argv[i];
        if (val == paramMetodo) {
            metodo = atoi((argv[i + 1]));
            metodoOk = true;
        } else if (val == paramDatasetPath) {
            datasetPath = argv[i + 1];
            datasetPathOk = true;
        } else if (val == paramClasificacion) {
            archivoclasificacion = argv[i + 1];
            clasifOk = true;
        } else if (val == paramFrecuencyMin) {
            frecuenciaMinima = atof(argv[i + 1]);
        } else if (val == paramFrecuencyMax) {
            frecuenciaMaxima = atof(argv[i + 1]);
        } else if (val == paramK) {
            k_vecinos = atoi(argv[i + 1]);
        } else if (val == paramAlfa) {
            alfa = atof(argv[i + 1]);
        } else if (val == paramClasificacionReal) {
            archivoclasificacionReal = argv[i + 1];
        } else if (val == paramDatosExtra) {
            archivoDatosExtra = argv[i + 1];
        }
    }
    if (!(metodoOk && datasetPathOk && clasifOk)) {
        std::cout << "Parámetros incorrectos. Debe utilizar:" << endl
                << "./tp2 "
                << paramMetodo << " <method> "
                << paramDatasetPath << " <dataset-path> "
                << paramClasificacion << " <classif> "
                << "[" << paramClasificacionReal << " <clasifReal>] "
                << "[" << paramK << " <k de kNN>] "
                << "[" << paramAlfa << " <alfa de PCA>] "
                << "[" << paramFrecuencyMin << " <freq min aceptable>] "
                << "[" << paramFrecuencyMax << " <freq max aceptable>] "
                << "[" << paramVocabPath << " <archivo vocabulario>] "
                << "[" << paramDatosExtra << " <archivo extra data>] "
                ;
        exit(0);
    }
}

void prosesarDataset(int argc, char** argv) {
    auto filter_out = [] (const int token, const FrecuencyVocabularyMap & vocabulary) {
        /**
         *  Lambda para usar como filtro de vocabulario
         *  Retorna `true` si `token` debe eliminarse
         *  Retorna `false` si `token` no debe eliminarse
         **/
        double token_frecuency = vocabulary.at(token);
        return token_frecuency < frecuenciaMinima || token_frecuency > frecuenciaMaxima;
    };

    //std::string entries_path = "datos/imdb_tokenized.csv";
    build_vectorized_datasets(datasetPath, train_entries, test_entries, filter_out, vocab_path);
    bow_size = train_entries.begin()->second.bag_of_words.size();
    std::cerr
            << "Tamaño de los bags of words: " << bow_size << " tokens" << std::endl
            << "Tamaño del dataset de training: " << train_entries.size() << " entradas" << std::endl
            << "Tamaño del dataset de testing: " << test_entries.size() << " entradas" << std::endl;
}

void llenarMatricesYVectores(matrizReal & m, vectorEntero &v, vector<bool> &c, const VectorizedEntriesMap & entriesMapita) {
    unsigned int i = 0;
    for (auto it = entriesMapita.begin(); it != entriesMapita.end(); ++it) {
        v[i] = it->first;
        c[i] = it->second.is_positive;
        for (int j = 0; j < it->second.bag_of_words.size(); ++j) {
            m[i][j] = it->second.bag_of_words[j];
        }
        i++;
    }
}

int main(int argc, char** argv) {
    //Parseo de entrada
    procesarVariables(argc, argv);
    prosesarDataset(argc, argv);
    //armado de matrices
    cerr << "Armando matrices...";
    int componentes = bow_size;
    int trainSize = train_entries.size();
    int testSize = test_entries.size();
    vectorEntero traductorIndiceTrainEntry(trainSize, 0);
    vector<bool> clasesTrain(trainSize, false);
    vector<bool> clasesTest(testSize, false);
    vectorEntero traductorIndiceTestEntry(testSize, 0);
    matrizReal matrizTrain(trainSize, vectorReal(componentes, 0));
    matrizReal matrizTest(testSize, vectorReal(componentes, 0));
    llenarMatricesYVectores(matrizTrain, traductorIndiceTrainEntry, clasesTrain, train_entries);
    llenarMatricesYVectores(matrizTest, traductorIndiceTestEntry, clasesTest, test_entries);
    cerr << "                            \r";

    //resolución
    vectorReal distancias;
    vectorEntero indices;
    vector<bool> resultados;
    clock_t tiempo_inicio = clock();
    switch (metodo) {
        case 0: //kNN
            cerr << "Resolviendo por kNN...";
            for (unsigned int i = 0; i < matrizTest.size(); i++) {
                //cerr << "\r" << "Buscando vecinos para " << traductorIndiceTestEntry[i] << "...";
                buscar(k_vecinos, matrizTrain, matrizTest[i], indices, distancias);
                resultados.push_back(votar(2, clasesTrain, indices, distancias));
            }
            break;
        case 1: //kNN + PCA
            cerr << "\r" << "Resolviendo por PCA + kNN...             ";

            vectorReal medias(componentes, 0);
            matrizReal cov(componentes, vectorReal(componentes, 0));

            cerr << "\r" << "calculando medias...                     ";
            calcularMedias(matrizTrain, medias);
            cerr << "\r" << "calculando covarianzas...                ";
            matrizCovarianzas(matrizTrain, cov, medias); //centra la matriz train y calcula cov
            cerr << "\r" << "centrando test...                        ";
            centrarRespectoA(matrizTest, medias, trainSize); // centro la matriz test con las medias obtenidas de train

            alfa = min(alfa, componentes);
            cerr << "\r" << "obteniendo " << alfa << " vectores                   ";
            matrizReal Vt;
            obtenerAlfaVectores(cov, alfa, Vt);

            matrizReal nuevoTrain(trainSize, vectorReal(alfa, 0));
            matrizReal nuevoTest(testSize, vectorReal(alfa, 0));

            tc(Vt, matrizTrain, nuevoTrain);
            tc(Vt, matrizTest, nuevoTest);
            for (unsigned int i = 0; i < nuevoTest.size(); i++) {
                //cerr << "\r" << "Buscando vecinos para " << traductorIndiceTestEntry[i] << "...";
                buscar(k_vecinos, nuevoTrain, nuevoTest[i], indices, distancias);
                resultados.push_back(votar(2, clasesTrain, indices, distancias));
            }
            break;
    }
    clock_t tiempo_fin = clock();
    cerr << "                            \r";

    ofstream salida;
    salida.open(archivoclasificacion.c_str());

    ofstream salidaReal;
    salidaReal.open(archivoclasificacionReal.c_str());

    ofstream salidaOtrasCosas;
    salidaOtrasCosas.open(archivoDatosExtra.c_str());

    map<bool, map<bool, int> > casosResultado;
    /*casosResultado[true] = map<bool,int>();
    casosResultado[false] = map<bool,int>();
    casosResultado[true][false]=0;
    casosResultado[true][true]=0;
    casosResultado[false][false]=0;
    casosResultado[false][true]=0;*/

    for (unsigned int i_salida = 0; i_salida < resultados.size(); i_salida++) {
        salida << traductorIndiceTestEntry[i_salida]; //escupo el id de la review
        salida << ",";
        salida << (resultados[i_salida] ? "pos" : "neg"); // clasificacion
        salida << endl;
        salidaReal << traductorIndiceTestEntry[i_salida]; //escupo el id de la review
        salidaReal << ",";
        salidaReal << (clasesTest[i_salida] ? "pos" : "neg"); // clasificacion real
        salidaReal << endl;
        casosResultado[clasesTest[i_salida]][resultados[i_salida]]++;
    }

    string metodos[2] = {"kNN", "PCA + kNN"};
    salidaOtrasCosas
            << "parametros: "
            << "metodo: " << metodos[metodo] << endl
            << "dataset: " << datasetPath << endl
            << "clasificación obtenida: " << archivoclasificacion << endl 
            << "clasificación real: " << archivoclasificacionReal << endl
            << "k vecinos: " << k_vecinos << endl
            << "alfa componentes: " << alfa << endl
            << "rango frecuencia: ( " << frecuenciaMinima << " ; " << frecuenciaMaxima << " )" << endl
            << endl;
    salidaOtrasCosas << "tiempo: " << (double) (tiempo_fin - tiempo_inicio) / CLOCKS_PER_SEC << endl;
    double pos_tp = casosResultado[true][true],
            pos_fn = casosResultado[true][false],
            pos_fp = casosResultado[false][true],
            pos_tn = casosResultado[false][false];
    double neg_tp = pos_tn,
            neg_fn = pos_fp,
            neg_fp = pos_fn,
            neg_tn = pos_tp;
    double precisionPos = pos_tp / (pos_tp + pos_fp);
    double precisionNeg = neg_tp / (neg_tp + neg_fp);
    double recallPos = pos_tp / (pos_tp + pos_fn);
    double recallNeg = neg_tp / (neg_tp + neg_fn);
    double f1scorePos = 2 * precisionPos * recallPos / (precisionPos + recallPos);
    double f1scoreNeg = 2 * precisionNeg * recallNeg / (precisionNeg + recallNeg);

    salidaOtrasCosas
            << "Positivos" << endl
            << "tp: " << pos_tp << endl
            << "fn: " << pos_fn << endl
            << "fp: " << pos_fp << endl
            << "tn: " << pos_tn << endl
            << "precision: " << precisionPos << endl
            << "recall: " << recallPos << endl
            << "f1-score: " << f1scorePos << endl
            << endl;
    salidaOtrasCosas
            << "Negativos" << endl
            << "tp: " << neg_tp << endl
            << "fn: " << neg_fn << endl
            << "fp: " << neg_fp << endl
            << "tn: " << neg_tn << endl
            << "precision: " << precisionNeg << endl
            << "recall: " << recallNeg << endl
            << "f1-score: " << f1scoreNeg << endl
            << endl;
}
