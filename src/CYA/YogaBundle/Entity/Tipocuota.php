<?php

namespace CYA\YogaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Tipocuota
 *
 * @ORM\Table(name="tipocuota")
 * @UniqueEntity("nombre")
 * @ORM\Entity(repositoryClass="CYA\YogaBundle\Repository\TipocuotaRepository")
 */
class Tipocuota
{
    /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="tipocuota")
     */
    protected $usuarios;
 
    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float")
     */
    private $valor;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Tipocuota
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return Tipocuota
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

   

    /**
     * Add usuario
     *
     * @param \CYA\YogaBundle\Entity\Usuario $usuario
     *
     * @return Tipocuota
     */
    public function addUsuario(\CYA\YogaBundle\Entity\Usuario $usuario)
    {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \CYA\YogaBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\CYA\YogaBundle\Entity\Usuario $usuario)
    {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }
}
