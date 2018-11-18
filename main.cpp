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



using namespace std;

const string paramMetodo = "-m";
const string paramDatasetPath = "-d";
const string paramVocabPath = "-v";
const string paramClasificacion = "-o";
const string paramFrecuencyMin = "-fi";
const string paramFrecuencyMax = "-fa";
const string paramK = "-k";
const string paramAlfa = "-a";

const int clases = 2; // pos neg

//Parametros variables
int metodo = -1;
string datasetPath = "";
string vocab_path = "datos/vocab.csv";
string clasificacion = "classif.out";
double frecuenciaMinima = 0.1;
double frecuenciaMaxima = 0.5;
int k = 3;
int alfa = 0.5;
int bow_size = 0;


VectorizedEntriesMap train_entries;
VectorizedEntriesMap test_entries;

void procesarVariables(int argc, char** argv) {
    bool metodoOk=false,
         datasetPathOk=false,
         clasifOk=false;
    for (int i = 1; i < argc; i+=2) {
        string val = argv[i];
        if (val == paramMetodo) {
            metodo = atoi((argv[i+1]));
            metodoOk=true;
        } else if (val == paramDatasetPath) {
            datasetPath = argv[i+1];
            datasetPathOk=true;
        }else if (val == paramClasificacion) {
            clasificacion = argv[i+1];
            clasifOk=true;
        }else if (val == paramFrecuencyMin){
            frecuenciaMinima = atof(argv[i+1]);
        }else if (val == paramFrecuencyMax){
            frecuenciaMaxima = atof(argv[i+1]);
        }else if (val == paramK){
            k = atoi(argv[i+1]);
        }else if (val == paramAlfa){
            alfa = atof(argv[i+1]);
        }
    }
    if(! (metodoOk && datasetPathOk && clasifOk )){
        std::cout << "Parámetros incorrectos. Debe utilizar:" << endl
                << "./tp2 "
                << paramMetodo << " <method> "
                << paramDatasetPath << " <dataset-path> "
                << paramClasificacion << " <classif> "
                << "["<< paramK << " <k de kNN>] "
                << "["<<paramAlfa << " <alfa de PCA>] "
                << "["<<paramFrecuencyMin << " <freq min aceptable>] "
                << "["<<paramFrecuencyMax << " <freq max aceptable>] "
                << "["<<paramVocabPath << " <archivo vocabulario>] "
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

void llenarMatricesYVectores(matrizReal & m,vectorEntero &v, vector<bool> &c, const VectorizedEntriesMap & entriesMapita){
    unsigned int i=0;
    for (auto it = entriesMapita.begin();it != entriesMapita.end();++it) {
        v[i] = it->first;
        c[i] = it->second.is_positive;
        for(int j = 0; j < it->second.bag_of_words.size();++j){
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
    int componentes = bow_size;
    int trainSize = train_entries.size();
    int testSize = test_entries.size();
    vectorEntero traductorIndiceTrainEntry(trainSize,0);
    vector<bool> clasesTrain(trainSize,false);
    vector<bool> clasesTest(testSize,false);
    vectorEntero traductorIndiceTestEntry(testSize,0);
    matrizReal matrizTrain(trainSize,vectorReal(componentes,0));
    matrizReal matrizTest(testSize,vectorReal(componentes,0));
    llenarMatricesYVectores(matrizTrain,traductorIndiceTrainEntry,clasesTrain,train_entries);
    llenarMatricesYVectores(matrizTest,traductorIndiceTestEntry,clasesTest,test_entries);

    //resolución
    vectorReal distancias;
    vectorEntero indices;
    vector<bool> resultados;
    switch(metodo){
        case 0: //kNN
            for (unsigned int i = 0; i < matrizTest.size(); i++) {
                buscar(k, matrizTrain, matrizTest[i], indices, distancias);
                resultados.push_back(votar(2 , clasesTrain, indices, distancias));
            }
            break;
            break;
        case 1: //kNN + PCA
            vectorReal medias(componentes,0);
            calcularMedias(matrizTrain,medias);
            centrarRespectoA(matrizTrain, medias, matrizTrain.size());
            break;

    }
    
    for(auto res = resultados.begin(); res != resultados.end();++res){
        cout << "resultado " << ((*res)?"pos":"neg") << endl;
    }

}
