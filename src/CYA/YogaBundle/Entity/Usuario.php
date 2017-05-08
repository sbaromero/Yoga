<?php


namespace CYA\YogaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;



/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="CYA\YogaBundle\Repository\UsuarioRepository")
 * @UniqueEntity("dni")
 * @UniqueEntity("nombreusuario")
 * @ORM\HasLifecycleCallbacks()
 */
class Usuario implements AdvancedUserInterface, \Serializable
{
    
    /**
     * @ORM\OneToMany(targetEntity="Movimiento", mappedBy="usuario")
     */
    protected $movimientos;
 

    /**
     * @ORM\OneToMany(targetEntity="Alumnocc", mappedBy="usuario")
     */
    protected $alumnoccs;
 
    public function __construct()
    {
        $this->alumnoccs = new ArrayCollection();
        $this->movimientos = new ArrayCollection();

        $this->isActive = true;
    }
    
    /**
     * @ORM\ManyToOne(targetEntity="Tipocuota", inversedBy="usuarios")
     * @ORM\JoinColumn(name="tipocuota_id", referencedColumnName="id")
     */
    protected $tipocuota;
    
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
     * @ORM\Column(name="nombreusuario", type="string", length=100, unique=true)
     */
    private $nombreusuario;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=100)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="dni", type="string", length=10, unique=true)
     */
    private $dni;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=100, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=100, nullable=true)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=100, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=100, nullable=true)
     */
    private $mail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechanacimiento", type="datetime", nullable=true)
     */
    private $fechanacimiento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaingreso", type="datetime", nullable=true)
     */
    private $fechaingreso;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechareingreso", type="datetime", nullable=true)
     */
    private $fechareingreso;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="rol", type="string", length=20)
     */
    private $rol;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    
    
    /**
     * @var bool
     *
     * @ORM\Column(name="have_locker", type="boolean",nullable=true)
     */
    private $haveLocker;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="have_asoc", type="boolean",nullable=true)
     */
    private $haveAsoc;
    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;
    
  
     /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @Assert\Image(
     *     minWidth = 100,
     *     maxWidth = 1000,
     *     minHeight = 100,
     *     maxHeight = 1000
     * )
     */
    private $brochure;

    public function getBrochure()
    {
        return $this->brochure;
    }


    public function setBrochure($brochure)
    {
        $this->brochure = $brochure;

        return $this;
    }
  
  

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
     * Set nombreusuario
     *
     * @param string $nombreusuario
     *
     * @return Usuario
     */
    public function setNombreusuario($nombreusuario)
    {
        $this->nombreusuario = $nombreusuario;

        return $this;
    }

    /**
     * Get nombreusuario
     *
     * @return string
     */
    public function getNombreusuario()
    {
        return $this->nombreusuario;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Usuario
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
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Usuario
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set dni
     *
     * @param string $dni
     *
     * @return Usuario
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Usuario
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return Usuario
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Usuario
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
     * Set mail
     *
     * @param string $mail
     *
     * @return Usuario
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
     * Set fechanacimiento
     *
     * @param \DateTime $fechanacimiento
     *
     * @return Usuario
     */
    public function setFechanacimiento($fechanacimiento)
    {
        $this->fechanacimiento = $fechanacimiento;

        return $this;
    }

    /**
     * Get fechanacimiento
     *
     * @return \DateTime
     */
    public function getFechanacimiento()
    {
        return $this->fechanacimiento;
    }

    /**
     * Set fechaingreso
     *
     * @param \DateTime $fechaingreso
     *
     * @return Usuario
     */
    public function setFechaingreso($fechaingreso)
    {
        $this->fechaingreso = $fechaingreso;

        return $this;
    }

    /**
     * Get fechaingreso
     *
     * @return \DateTime
     */
    public function getFechaingreso()
    {
        return $this->fechaingreso;
    }

    /**
     * Set fechareingreso
     *
     * @param \DateTime $fechareingreso
     *
     * @return Usuario
     */
    public function setFechareingreso($fechareingreso)
    {
        $this->fechareingreso = $fechareingreso;

        return $this;
    }

    /**
     * Get fechareingreso
     *
     * @return \DateTime
     */
    public function getFechareingreso()
    {
        return $this->fechareingreso;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set rol
     *
     * @param string $rol
     *
     * @return Usuario
     */
    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return string
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Usuario
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
     * Set haveLocker
     *
     * @param boolean $haveLocker
     *
     * @return Usuario
     */
    public function setHaveLocker($haveLocker)
    {
        $this->haveLocker = $haveLocker;

        return $this;
    }

    /**
     * Get haveLocker
     *
     * @return bool
     */
    public function getHaveLocker()
    {
        return $this->haveLocker;
    }

     /**
     * Set haveAsoc
     *
     * @param boolean $haveAsoc
     *
     * @return Usuario
     */
    public function setHaveAsoc($haveAsoc)
    {
        $this->haveAsoc = $haveAsoc;

        return $this;
    }

    /**
     * Get haveAsoc
     *
     * @return bool
     */
    public function getHaveAsoc()
    {
        return $this->haveAsoc;
    }



    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Usuario
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Usuario
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
       /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set tipocuota
     *
     * @param \CYA\YogaBundle\Entity\Tipocuota $tipocuota
     *
     * @return Usuario
     */
    public function setTipocuota(\CYA\YogaBundle\Entity\Tipocuota $tipocuota = null)
    {
        $this->tipocuota = $tipocuota;

        return $this;
    }

    /**
     * Get tipocuota
     *
     * @return \CYA\YogaBundle\Entity\Tipocuota
     */
    public function getTipocuota()
    {
        return $this->tipocuota;
    }

    /**
     * Add alumnocc
     *
     * @param \CYA\YogaBundle\Entity\Alumnocc $alumnocc
     *
     * @return Usuario
     */
    public function addAlumnocc(\CYA\YogaBundle\Entity\Alumnocc $alumnocc)
    {
        $this->alumnoccs[] = $alumnocc;

        return $this;
    }

    /**
     * Remove alumnocc
     *
     * @param \CYA\YogaBundle\Entity\Alumnocc $alumnocc
     */
    public function removeAlumnocc(\CYA\YogaBundle\Entity\Alumnocc $alumnocc)
    {
        $this->alumnoccs->removeElement($alumnocc);
    }

    /**
     * Get alumnoccs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlumnoccs()
    {
        return $this->alumnoccs;
    }

    /**
     * Add movimiento
     *
     * @param \CYA\YogaBundle\Entity\Movimiento $movimiento
     *
     * @return Usuario
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

    public function getUsername()
    {
        return $this->nombreusuario;
    }
    
    public function getRoles()
    {
        return array($this->rol);
    }
    
    public function getSalt()
    {
        return null;
    }
    
    public function eraseCredentials()
    {

    }
    
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->nombreusuario,
            $this->password,
            $this->isActive
        ));
    }
    
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->nombreusuario,
            $this->password,
            $this->isActive
        ) = unserialize($serialized);
    }
    
     public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }
    
    /**
     * Get nombrecompleto
     *
     * @return string
     */
    public function getNombrecompleto(){
        return $this->nombre . " " . $this->apellido;
    }
    
    
}
