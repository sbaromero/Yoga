<?php

namespace CYA\YogaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tipoproducto
 *
 * @ORM\Table(name="tipoproducto")
 * @ORM\Entity(repositoryClass="CYA\YogaBundle\Repository\TipoproductoRepository")
 */
class Tipoproducto
{
    /**
     * @ORM\OneToMany(targetEntity="Producto", mappedBy="tipoproducto")
     */
    protected $productos;
    
    public function __construct()
    {
        $this->productos = new ArrayCollection();
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
     * @ORM\Column(name="descripcion", type="string", length=100)
     */
    private $descripcion;


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
     * @return Tipoproducto
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
     * Add producto
     *
     * @param \CYA\YogaBundle\Entity\Producto $producto
     *
     * @return Tipoproducto
     */
    public function addProducto(\CYA\YogaBundle\Entity\Producto $producto)
    {
        $this->productos[] = $producto;

        return $this;
    }

    /**
     * Remove producto
     *
     * @param \CYA\YogaBundle\Entity\Producto $producto
     */
    public function removeProducto(\CYA\YogaBundle\Entity\Producto $producto)
    {
        $this->productos->removeElement($producto);
    }

    /**
     * Get productos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductos()
    {
        return $this->productos;
    }
}
