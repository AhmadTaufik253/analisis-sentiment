<?php
namespace App\Helpers;

class Tokenizer {
    public function tokenize($text) {
        return explode(' ', $text);
    }
}
