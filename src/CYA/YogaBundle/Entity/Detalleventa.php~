<?php

namespace CYA\YogaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Detalleventa
 *
 * @ORM\Table(name="detalleventa")
 * @ORM\Entity(repositoryClass="CYA\YogaBundle\Repository\DetalleventaRepository")
 */
class Detalleventa
{
    
    /**
     * @ORM\ManyToOne(targetEntity="Maestroventa", inversedBy="detalleventas")
     * @ORM\JoinColumn(name="maestroventa_id", referencedColumnName="id")
     */
    protected $maestroventa;
    
    /**
     * @ORM\ManyToOne(targetEntity="Producto", inversedBy="detalleventas")
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     */
    protected $producto;
    
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
     * @ORM\Column(name="nombreproducto", type="string", length=255)
     */
    private $nombreproducto;

    /**
     * @var float
     *
     * @ORM\Column(name="precioproducto", type="float")
     */
    private $precioproducto;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;


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
     * Set nombreproducto
     *
     * @param string $nombreproducto
     *
     * @return Detalleventa
     */
    public function setNombreproducto($nombreproducto)
    {
        $this->nombreproducto = $nombreproducto;

        return $this;
    }

    /**
     * Get nombreproducto
     *
     * @return string
     */
    public function getNombreproducto()
    {
        return $this->nombreproducto;
    }

    /**
     * Set precioproducto
     *
     * @param float $precioproducto
     *
     * @return Detalleventa
     */
    public function setPrecioproducto($precioproducto)
    {
        $this->precioproducto = $precioproducto;

        return $this;
    }

    /**
     * Get precioproducto
     *
     * @return float
     */
    public function getPrecioproducto()
    {
        return $this->precioproducto;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return Detalleventa
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return int
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set maestroventa
     *
     * @param \CYA\YogaBundle\Entity\Maestroventa $maestroventa
     *
     * @return Detalleventa
     */
    public function setMaestroventa(\CYA\YogaBundle\Entity\Maestroventa $maestroventa = null)
    {
        $this->maestroventa = $maestroventa;

        return $this;
    }

    /**
     * Get maestroventa
     *
     * @return \CYA\YogaBundle\Entity\Maestroventa
     */
    public function getMaestroventa()
    {
        return $this->maestroventa;
    }

    /**
     * Set producto
     *
     * @param \CYA\YogaBundle\Entity\Producto $producto
     *
     * @return Detalleventa
     */
    public function setProducto(\CYA\YogaBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \CYA\YogaBundle\Entity\Producto
     */
    public function getProducto()
    {
        return $this->producto;
    }
}
