<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Client extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    /** Change le mot de passe et l'encrypte.
     * @param string $pass Le mot de passe en clair.
     * @return $this
     */
    public function setPassword(string $pass): static
    {
        $this->attributes['password'] = password_hash($pass, PASSWORD_BCRYPT);

        return $this;
    }

    /** Vérifie si le mot de passe est correct.
     * @param string $pass Le mot de passe en clair.
     * @return bool True si le password donné correspond à celui de l'utilisateur.
     */
    public function checkPassword(string $pass): bool
    {
        return password_verify($pass, $this->attributes['password']);
    }

    public function getCodeMDPOublie(): string
    {
        return password_hash($this->attributes['password'], PASSWORD_BCRYPT);
    }
}
