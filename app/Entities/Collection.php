<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

/**
 * L'entité pour la collection.
 */
class Collection extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}
