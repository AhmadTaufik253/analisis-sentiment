<?php
namespace App\Helpers;

class TfIdfTransformer {
    private $idf = [];

    public function fit($vectors) {
        $numDocuments = count($vectors);
        $numTerms = count($vectors[0]);
        for ($i = 0; $i < $numTerms; $i++) {
            $docCount = 0;
            foreach ($vectors as $vector) {
                if ($vector[$i] > 0) {
                    $docCount++;
                }
            }
            $this->idf[$i] = log10($numDocuments / ($docCount + 1));
        }
    }

    public function transform($vectors) {
        $tfidfVectors = [];
        foreach ($vectors as $vector) {
            $tfidfVector = [];
            foreach ($vector as $index => $termCount) {
                if (isset($this->idf[$index])) {
                    $tf = $termCount / array_sum($vector);
                    $tfidfVector[] = $tf * $this->idf[$index];
                } else {
                    $tfidfVector[] = 0;
                }
            }
            $tfidfVectors[] = $tfidfVector;
        }
        return $tfidfVectors;
    }
}
