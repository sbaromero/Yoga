<?php

namespace CYA\YogaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proveedorcc
 *
 * @ORM\Table(name="proveedorcc")
 * @ORM\Entity(repositoryClass="CYA\YogaBundle\Repository\ProveedorccRepository")
 */
class Proveedorcc
{

    
    /**
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     */
    protected $proveedor;
    
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
     * @ORM\Column(name="fechavencimiento", type="datetime")
     */
    private $fechavencimiento;

    /**
     * @var float
     *
     * @ORM\Column(name="deuda", type="float")
     */
    private $deuda;

    /**
     * @var float
     *
     * @ORM\Column(name="pagado", type="float")
     */
    private $pagado;

    /**
     * @var string
     *
     * @ORM\Column(name="factura", type="string", length=100)
     */
    private $factura;


    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=256)
     */
    private $tipo;

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
     * Set fechavencimiento
     *
     * @param \DateTime $fechavencimiento
     *
     * @return Proveedorcc
     */
    public function setFechavencimiento($fechavencimiento)
    {
        $this->fechavencimiento = $fechavencimiento;

        return $this;
    }

    /**
     * Get fechavencimiento
     *
     * @return \DateTime
     */
    public function getFechavencimiento()
    {
        return $this->fechavencimiento;
    }

    /**
     * Set deuda
     *
     * @param float $deuda
     *
     * @return Proveedorcc
     */
    public function setDeuda($deuda)
    {
        $this->deuda = $deuda;

        return $this;
    }

    /**
     * Get deuda
     *
     * @return float
     */
    public function getDeuda()
    {
        return $this->deuda;
    }

    /**
     * Set pagado
     *
     * @param float $pagado
     *
     * @return Proveedorcc
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
     * Set factura
     *
     * @param string $factura
     *
     * @return Proveedorcc
     */
    public function setFactura($factura)
    {
        $this->factura = $factura;

        return $this;
    }

    /**
     * Get factura
     *
     * @return string
     */
    public function getFactura()
    {
        return $this->factura;
    }
    
    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Proveedorcc
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }    

    /**
     * Set proveedor
     *
     * @param \CYA\YogaBundle\Entity\Proveedor $proveedor
     *
     * @return Proveedorcc
     */
    public function setProveedor(\CYA\YogaBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;

        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \CYA\YogaBundle\Entity\Proveedor
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Add movimiento
     *
     * @param \CYA\YogaBundle\Entity\Movimiento $movimiento
     *
     * @return Proveedorcc
     */
    public function addMovimiento(\CYA\YogaBundle\Entity\Movimiento $movimiento)
    {
        $this->movimientos[] = $movimiento;

        return $this;
    }

    /**
     * Remove movimiento
     *
     * @param \CYA\YogaBundle\Entity\Movimiento $movimiento
     */
    public function removeMovimiento(\CYA\YogaBundle\Entity\Movimiento $movimiento)
    {
        $this->movimientos->removeElement($movimiento);
    }

    /**
     * Get movimientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientos()
    {
        return $this->movimientos;
    }
}
