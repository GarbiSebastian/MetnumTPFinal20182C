/* 
 * File:   main.cpp
 * Author: sebastian
 *
 * Created on 12 de noviembre de 2018, 09:46
 */

#include <cstdlib>
#include "src/vector_builder.h"

using namespace std;

const string paramMetodo = "-m";
const string paramDatasetPath = "-d";
const string paramClasificacion = "-o";
const string paramFrecuencyMin = "-fi";
const string paramFrecuencyMax = "-fa";

//Parametros variables
int metodo = -1;
string datasetPath = "";
string clasificacion = "classif.out";
double frecuenciaMinima = 0.1;
double frecuenciaMaxima = 0.5;

VectorizedEntriesMap train_entries;
VectorizedEntriesMap test_entries;

int mainCatedra(int argc, char** argv) {
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
    build_vectorized_datasets(datasetPath, train_entries, test_entries, filter_out);
    int N = train_entries.begin()->second.bag_of_words.size();
    std::cerr
            << "Tamaño de los bags of words: " << N << " tokens" << std::endl
            << "Tamaño del dataset de training: " << train_entries.size() << " entradas" << std::endl
            << "Tamaño del dataset de testing: " << test_entries.size() << " entradas" << std::endl;

    return 0;
}

void procesarVariables(int argc, char** argv) {
    bool metodoOk=false, datasetPathOk=false, clasifOk=false;
    
    for (int i = 0; i < argc; i+=2) {
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
        }
    }
    
    

}

/*
 * 
 */
int main(int argc, char** argv) {
    procesarVariables(argc, argv);

    return mainCatedra(argc, argv);
    //    return 0;
}

