<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClasseEtudiant
 *
 * @ORM\Table(name="classe_etudiant")
 * @ORM\Entity
 */
class ClasseEtudiant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_classe", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idClasse;

    /**
     * @var int
     *
     * @ORM\Column(name="id_etudiant", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idEtudiant;

    public function getIdClasse()
    {
        return $this->idClasse;
    }

    public function getIdEtudiant()
    {
        return $this->idEtudiant;
    }


}
