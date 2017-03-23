<?php

namespace CYA\YogaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Lockersocio
 *
 * @ORM\Table(name="lockersocio")
 * @ORM\Entity(repositoryClass="CYA\YogaBundle\Repository\LockersocioRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Lockersocio
{

    /**
     * @ORM\ManyToOne(targetEntity="Locker", inversedBy="lockersocio")
     * @ORM\JoinColumn(name="locker_id", referencedColumnName="id")
     */
    protected $locker;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="lockersocio")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuario;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechainicio", type="datetime")
     */
    private $fechainicio;

     /**
     * @var float
     *
     * @ORM\Column(name="monto", type="float")
     */
    private $monto;

     /**
     * @var string
     *
     * @ORM\Column(name="comentario", type="string", length=100, nullable=true)
     */
    private $comentario;
    
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
     * Set fechainicio
     *
     * @param \DateTime $fechainicio
     *
     * @return lockersocio
     */
    public function setFechainicio($fechainicio)
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    /**
     * Get fechainicio
     *
     * @return \DateTime
     */
    public function getFechainicio()
    {
        return $this->fechainicio;
    }
    
    /**
     * Set fechafin
     *
     * @param \DateTime $fechafin
     *
     * @return Lockersocio
     */
    public function setFechafin($fechafin)
    {
        $this->fechafin = $fechafin;

        return $this;
    }

    /**
     * Get fechafin
     *
     * @return \DateTime
     */
    public function getFechafin()
    {
        return $this->fechafin;
    }


    /**
     * Set monto
     *
     * @param float $monto
     *
     * @return Lockersocio
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return float
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set pagado
     *
     * @param float $pagado
     *
     * @return Alumnocc
     */
    public function setPagado($pagado)
    {
        $this->pagado = $pagado;

        return $this;
    }

    /**
     * Get pagado
     *
     * @return float
     */
    public function getPagado()
    {
        return $this->pagado;
    }

    
    /**
     * Set comentario
     *
     * @param string $comentario
     *
     * @return alumnocc
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }
    
    /**
     * Set usuario
     *
     * @param \CYA\YogaBundle\Entity\Usuario $usuario
     *
     * @return Lockersocio
     */
    public function setUsuario(\CYA\YogaBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \CYA\YogaBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    
    /**
     * Set locker
     *
     * @param \CYA\YogaBundle\Entity\Locker $locker
     *
     * @return Lockersocio
     */
    public function setLocker(\CYA\YogaBundle\Entity\Locker $locker = null)
    {
        $this->locker = $locker;

        return $this;
    }

    /**
     * Get locker
     *
     * @return \CYA\YogaBundle\Entity\Locker
     */
    public function getLocker()
    {
        return $this->locker;
    }
    
    
    
    
}
