<?php

namespace App\Service;

class Censurator {

    public function purify($string) {
        $termes = ["merde", "chier", "putain", "bordel", "cul"];
        $etoiles = "*****";

        $newphrase = str_ireplace($termes, $etoiles, $string);

        return $newphrase;
    }
}