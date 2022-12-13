<?php

/**
 * Énumération des tailles possibles des vêtements et des posters.
 * Est une classe fille de string.
 */
enum Taille: string
{
    case XS = 'XS';
    case S = 'S';
    case M = 'M';
    case L = 'L';
    case XL = 'XL';
    case XXL = 'XXL';
    case A0 = 'A0';
    case A1 = 'A1';
    case A2 = 'A2';
    case A3 = 'A3';

    /**
     * Retourne le nom de la catégorie de la taille.
     * @return string Soit 'poster' ou 'vetement'.
     */
    public function categorie(): string
    {
        if (in_array($this, self::posters())) {
            return 'poster';
        }
        return 'vetement';
    }

    /**
     * @return bool Vrai si la taille correspond à un poster.
     */
    public function estPoster(): bool
    {
        return $this->categorie() == 'poster';
    }

    /**
     * @return bool Vrai si la taille correspond à un vêtement.
     */
    public function estVetement(): bool
    {
        return $this->categorie() == 'vetement';
    }

    /**
     * @return Taille[] Toutes les tailles de poster disponibles.
     */
    public static function posters(): array
    {
        return [
            self::A0,
            self::A1,
            self::A2,
            self::A3,
        ];
    }

    /**
     * @return Taille[] Toutes les tailles de vêtement disponibles.
     */
    public static function vetements(): array
    {
        return [
            self::XS,
            self::S,
            self::M,
            self::L,
            self::XL,
            self::XXL,
        ];
    }
}