<?php

namespace CYA\YogaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Auxiliarventa
 *
 * @ORM\Table(name="auxiliarventa")
 * @ORM\Entity(repositoryClass="CYA\YogaBundle\Repository\AuxiliarventaRepository")
 */
class Auxiliarventa
{
    /**
     * @ORM\ManyToOne(targetEntity="Producto", inversedBy="auxiliarventas")
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
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @var float
     *
     * @ORM\Column(name="monto", type="float")
     */
    private $monto;


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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return Auxiliarventa
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
     * Set monto
     *
     * @param float $monto
     *
     * @return Auxiliarventa
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
     * Set producto
     *
     * @param \CYA\YogaBundle\Entity\Producto $producto
     *
     * @return Auxiliarventa
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
