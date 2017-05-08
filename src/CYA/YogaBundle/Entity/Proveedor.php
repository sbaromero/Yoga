<?php

namespace CYA\YogaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Proveedor
 *
 * @ORM\Table(name="proveedor")
 * @ORM\Entity(repositoryClass="CYA\YogaBundle\Repository\ProveedorRepository")
 */
class Proveedor
{
    /**
     * @ORM\OneToMany(targetEntity="Proveedorcc", mappedBy="proveedor")
     */
    protected $proveedorcc;
    
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
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=100, nullable=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=100)
     */
    private $telefono;


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
     * @return Proveedor
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
     * Set mail
     *
     * @param string $mail
     *
     * @return Proveedor
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Proveedor
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Add producto
     *
     * @param \CYA\YogaBundle\Entity\Producto $producto
     *
     * @return Proveedor
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

    /**
     * Add proveedorcc
     *
     * @param \CYA\YogaBundle\Entity\Proveedorcc $proveedorcc
     *
     * @return Proveedor
     */
    public function addProvedorcc(\CYA\YogaBundle\Entity\Proveedorcc $proveedorcc)
    {
        $this->proveedorccs[] = $proveedorcc;

        return $this;
    }

    /**
     * Remove proveedorcc
     *
     * @param \CYA\YogaBundle\Entity\Proveedorcc $proveedorcc
     */
    public function removeProvedorcc(\CYA\YogaBundle\Entity\Proveedorcc $proveedorcc)
    {
        $this->proveedorccs->removeElement($proveedorcc);
    }

    /**
     * Get proveedorccs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProveedorccs()
    {
        return $this->proveedorccs;
    }

    /**
     * Add proveedorcc
     *
     * @param \CYA\YogaBundle\Entity\Proveedorcc $proveedorcc
     *
     * @return Proveedor
     */
    public function addProveedorcc(\CYA\YogaBundle\Entity\Proveedorcc $proveedorcc)
    {
        $this->proveedorccs[] = $proveedorcc;

        return $this;
    }

    /**
     * Remove proveedorcc
     *
     * @param \CYA\YogaBundle\Entity\Proveedorcc $proveedorcc
     */
    public function removeProveedorcc(\CYA\YogaBundle\Entity\Proveedorcc $proveedorcc)
    {
        $this->proveedorccs->removeElement($proveedorcc);
    }
}
