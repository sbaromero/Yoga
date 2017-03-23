<?php

namespace CYA\YogaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Maestroventa
 *
 * @ORM\Table(name="maestroventa")
 * @ORM\Entity(repositoryClass="CYA\YogaBundle\Repository\MaestroventaRepository")
 */
class Maestroventa
{
    
    /**
     * @ORM\OneToOne(targetEntity="Movimiento", mappedBy="maestroventa")
     */
    protected $movimiento;
    
    /**
     * @ORM\OneToOne(targetEntity="Alumnocc", mappedBy="maestroventa")
     */
    protected $alumnocc;

    /**
     * @ORM\OneToMany(targetEntity="Detalleventa", mappedBy="maestroventa")
     */
    protected $detalleventas;
 
    public function __construct()
    {
        $this->detalleventas = new ArrayCollection();
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="tipopago", type="string", length=10)
     */
    private $tipopago;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Maestroventa
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set tipopago
     *
     * @param string $tipopago
     *
     * @return Maestroventa
     */
    public function setTipopago($tipopago)
    {
        $this->tipopago = $tipopago;

        return $this;
    }

    /**
     * Get tipopago
     *
     * @return string
     */
    public function getTipopago()
    {
        return $this->tipopago;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return Maestroventa
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }


    /**
     * Add detalleventa
     *
     * @param \CYA\YogaBundle\Entity\Detalleventa $detalleventa
     *
     * @return Maestroventa
     */
    public function addDetalleventa(\CYA\YogaBundle\Entity\Detalleventa $detalleventa)
    {
        $this->detalleventas[] = $detalleventa;

        return $this;
    }

    /**
     * Remove detalleventa
     *
     * @param \CYA\YogaBundle\Entity\Detalleventa $detalleventa
     */
    public function removeDetalleventa(\CYA\YogaBundle\Entity\Detalleventa $detalleventa)
    {
        $this->detalleventas->removeElement($detalleventa);
    }

    /**
     * Get detalleventas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetalleventas()
    {
        return $this->detalleventas;
    }

    /**
     * Set movimiento
     *
     * @param \CYA\YogaBundle\Entity\Movimiento $movimiento
     *
     * @return Maestroventa
     */
    public function setMovimiento(\CYA\YogaBundle\Entity\Movimiento $movimiento = null)
    {
        $this->movimiento = $movimiento;

        return $this;
    }

    /**
     * Get movimiento
     *
     * @return \CYA\YogaBundle\Entity\Movimiento
     */
    public function getMovimiento()
    {
        return $this->movimiento;
    }

    /**
     * Set alumnocc
     *
     * @param \CYA\YogaBundle\Entity\Alumnocc $alumnocc
     *
     * @return Maestroventa
     */
    public function setAlumnocc(\CYA\YogaBundle\Entity\Alumnocc $alumnocc = null)
    {
        $this->alumnocc = $alumnocc;

        return $this;
    }

    /**
     * Get alumnocc
     *
     * @return \CYA\YogaBundle\Entity\Alumnocc
     */
    public function getAlumnocc()
    {
        return $this->alumnocc;
    }
}
