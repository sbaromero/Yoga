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
     * @var bool
     *
     * @ORM\Column(name="instructorado", type="boolean")
     */
    private $instructorado;

    /**
     * @var bool
     *
     * @ORM\Column(name="clasesyoga", type="boolean")
     */
    private $clasesyoga;

    /**
     * @var bool
     *
     * @ORM\Column(name="asociacion", type="boolean")
     */
    private $asociacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="profesorado", type="boolean")
     */
    private $profesorado;

    /**
     * @var bool
     *
     * @ORM\Column(name="posgrado", type="boolean")
     */
    private $posgrado;

    /**
     * @var bool
     *
     * @ORM\Column(name="casillero", type="boolean")
     */
    private $casillero;

    /**
     * @var bool
     *
     * @ORM\Column(name="otro", type="boolean")
     */
    private $otro;


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
     * Set instructorado
     *
     * @param boolean $instructorado
     *
     * @return Tipocuota
     */
    public function setInstructorado($instructorado)
    {
        $this->instructorado = $instructorado;

        return $this;
    }

    /**
     * Get instructorado
     *
     * @return bool
     */
    public function getInstructorado()
    {
        return $this->instructorado;
    }

    /**
     * Set clasesyoga
     *
     * @param boolean $clasesyoga
     *
     * @return Tipocuota
     */
    public function setClasesyoga($clasesyoga)
    {
        $this->clasesyoga = $clasesyoga;

        return $this;
    }

    /**
     * Get clasesyoga
     *
     * @return bool
     */
    public function getClasesyoga()
    {
        return $this->clasesyoga;
    }

    /**
     * Set asociacion
     *
     * @param boolean $asociacion
     *
     * @return Tipocuota
     */
    public function setAsociacion($asociacion)
    {
        $this->asociacion = $asociacion;

        return $this;
    }

    /**
     * Get asociacion
     *
     * @return bool
     */
    public function getAsociacion()
    {
        return $this->asociacion;
    }

    /**
     * Set profesorado
     *
     * @param boolean $profesorado
     *
     * @return Tipocuota
     */
    public function setProfesorado($profesorado)
    {
        $this->profesorado = $profesorado;

        return $this;
    }

    /**
     * Get profesorado
     *
     * @return bool
     */
    public function getProfesorado()
    {
        return $this->profesorado;
    }

    /**
     * Set posgrado
     *
     * @param boolean $posgrado
     *
     * @return Tipocuota
     */
    public function setPosgrado($posgrado)
    {
        $this->posgrado = $posgrado;

        return $this;
    }

    /**
     * Get posgrado
     *
     * @return bool
     */
    public function getPosgrado()
    {
        return $this->posgrado;
    }

    /**
     * Set casillero
     *
     * @param boolean $casillero
     *
     * @return Tipocuota
     */
    public function setCasillero($casillero)
    {
        $this->casillero = $casillero;

        return $this;
    }

    /**
     * Get casillero
     *
     * @return bool
     */
    public function getCasillero()
    {
        return $this->casillero;
    }

    /**
     * Set otro
     *
     * @param boolean $otro
     *
     * @return Tipocuota
     */
    public function setOtro($otro)
    {
        $this->otro = $otro;

        return $this;
    }

    /**
     * Get otro
     *
     * @return bool
     */
    public function getOtro()
    {
        return $this->otro;
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
