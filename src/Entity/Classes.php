<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Classes
 *
 * @ORM\Table(name="classes")
 * @ORM\Entity
 */
class Classes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="classe", type="string", length=25, nullable=false)
     */
    private $classe;

    public function getId()
    {
        return $this->id;
    }

    public function getClasse()
    {
        return $this->classe;
    }

    public function setClasse(string $classe)
    {
        $this->classe = $classe;

        return $this;
    }


}
