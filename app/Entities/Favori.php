<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

/**
 * L'entité pour l'ajout en favori
 */
class Favori extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}
