/* 
 * File:   main.cpp
 * Author: sebastian
 *
 * Created on 12 de noviembre de 2018, 09:46
 */

#include <cstdlib>
#include "src/vector_builder.h"

using namespace std;

int mainCatedra(int argc, char** argv) {
    auto filter_out = [] (const int token, const FrecuencyVocabularyMap & vocabulary) {
        /**
         *  Lambda para usar como filtro de vocabulario
         *  Retorna `true` si `token` debe eliminarse
         *  Retorna `false` si `token` no debe eliminarse
         **/
        double token_frecuency = vocabulary.at(token);
        return token_frecuency < 0.1 || token_frecuency > 0.5;
    };
    std::string entries_path = "datos/imdb_tokenized.csv";
    VectorizedEntriesMap train_entries;
    VectorizedEntriesMap test_entries;
    build_vectorized_datasets(entries_path, train_entries, test_entries, filter_out);
    int N = train_entries.begin()->second.bag_of_words.size();

    std::cerr
            << "Tamaño de los bags of words: " << N << " tokens" << std::endl
            << "Tamaño del dataset de training: " << train_entries.size() << " entradas" << std::endl
            << "Tamaño del dataset de testing: " << test_entries.size() << " entradas" << std::endl;

    return 0;
}

/*
 * 
 */
int main(int argc, char** argv) {
    return mainCatedra(argc, argv);
    //    return 0;
}

