<?php
namespace App\Helpers;

class Vectorizer {
    private $tokenizer;
    private $vocabulary = [];

    public function __construct($tokenizer) {
        $this->tokenizer = $tokenizer;
    }

    public function fit($documents) {
        foreach ($documents as $document) {
            $tokens = $this->tokenizer->tokenize($document);
            foreach ($tokens as $token) {
                if (!in_array($token, $this->vocabulary)) {
                    $this->vocabulary[] = $token;
                }
            }
        }
    }

    public function transform($documents) {
        $vectors = [];
        foreach ($documents as $document) {
            $tokens = $this->tokenizer->tokenize($document);
            $vector = array_fill(0, count($this->vocabulary), 0);
            foreach ($tokens as $token) {
                if (($index = array_search($token, $this->vocabulary)) !== false) {
                    $vector[$index]++;
                }
            }
            $vectors[] = $vector;
        }
        return $vectors;
    }

    public function getVocabulary() {
        return $this->vocabulary;
    }
}
