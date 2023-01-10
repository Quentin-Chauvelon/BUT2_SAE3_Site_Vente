<?php

    /**
     * getExtensionImage retourne l'extension de l'image au chemin donné en paramètre.
     * C'est un design pattern de factory.
     * En effet elle retourne une extension en fonction du paramètre chemin.
     * @param $chemin string Le chemin de l'image
     * @return string L'extension de l'image
     */

    function getExtensionImage(string $chemin): string {
        if (file_exists($chemin . ".jpg")) {
            return ".jpg";
        }

        elseif (file_exists($chemin . ".png")) {
            return ".png";
        }

        elseif (file_exists($chemin . ".jpeg")) {
            return "jpeg";
        }
         
        else {
            return "";
        }
    }

?>