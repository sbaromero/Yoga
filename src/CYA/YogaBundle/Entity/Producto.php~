<?php

namespace CYA\YogaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Producto
 *
 * @ORM\Table(name="producto")
 * @UniqueEntity("descripcion")
 * @ORM\Entity(repositoryClass="CYA\YogaBundle\Repository\ProductoRepository")
 */
class Producto
{
    
    /**
     * @ORM\OneToMany(targetEntity="Auxiliarventa", mappedBy="producto")
     */
    protected $auxiliarventas;
    
    /**
     * @ORM\OneToMany(targetEntity="Detalleventa", mappedBy="producto")
     */
    protected $detalleventas;
 
    public function __construct()
    {
        $this->auxiliarventas = new ArrayCollection();
        $this->detalleventas = new ArrayCollection();
    }
    
    /**
     * @ORM\ManyToOne(targetEntity="Tipoproducto", inversedBy="productos")
     * @ORM\JoinColumn(name="tipoproducto_id", referencedColumnName="id")
     */
    protected $tipoproducto;
    
    /**
     * @ORM\ManyToOne(targetEntity="Proveedor", inversedBy="productos")
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
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=100)
     */
    private $descripcion;
    
    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock;
    
    /**
     * @var float
     *
     * @ORM\Column(name="costo", type="float")
     */
    private $costo;
    
    /**
     * @var float
     *
     * @ORM\Column(name="preciolista", type="float")
     */
    private $preciolista;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", options={"default"=1})
     */
    private $isActive;


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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Producto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Producto
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }
    
    /**
     * Get stock
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }
    
    /**
     * Set costo
     *
     * @param float $costo
     *
     * @return Producto
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }
    
    /**
     * Set preciolista
     *
     * @param float $preciolista
     *
     * @return Producto
     */
    public function setPreciolista($preciolista)
    {
        $this->preciolista = $preciolista;

        return $this;
    }

    /**
     * Get preciolista
     *
     * @return float
     */
    public function getPreciolista()
    {
        return $this->preciolista;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Producto
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set tipoproducto
     *
     * @param \CYA\YogaBundle\Entity\Tipoproducto $tipoproducto
     *
     * @return Producto
     */
    public function setTipoproducto(\CYA\YogaBundle\Entity\Tipoproducto $tipoproducto = null)
    {
        $this->tipoproducto = $tipoproducto;

        return $this;
    }

    /**
     * Get tipoproducto
     *
     * @return \CYA\YogaBundle\Entity\Tipoproducto
     */
    public function getTipoproducto()
    {
        return $this->tipoproducto;
    }

    /**
     * Set proveedor
     *
     * @param \CYA\YogaBundle\Entity\Proveedor $proveedor
     *
     * @return Producto
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
     * Add auxiliarventa
     *
     * @param \CYA\YogaBundle\Entity\Auxiliarventa $auxiliarventa
     *
     * @return Producto
     */
    public function addAuxiliarventa(\CYA\YogaBundle\Entity\Auxiliarventa $auxiliarventa)
    {
        $this->auxiliarventas[] = $auxiliarventa;

        return $this;
    }

    /**
     * Remove auxiliarventa
     *
     * @param \CYA\YogaBundle\Entity\Auxiliarventa $auxiliarventa
     */
    public function removeAuxiliarventa(\CYA\YogaBundle\Entity\Auxiliarventa $auxiliarventa)
    {
        $this->auxiliarventas->removeElement($auxiliarventa);
    }

    /**
     * Get auxiliarventas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuxiliarventas()
    {
        return $this->auxiliarventas;
    }


    /**
     * Add detalleventa
     *
     * @param \CYA\YogaBundle\Entity\Detalleventa $detalleventa
     *
     * @return Producto
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
}
