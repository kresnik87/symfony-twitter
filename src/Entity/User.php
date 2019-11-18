<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\GroupInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\MaxDepth;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\EntityListeners({"App\EventListener\UserListener"})
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    const ROLE_USER = "ROLE_USER";
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_DEFAULT = self::ROLE_USER;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"user-read","device-write"})
     */
    protected $id;
    


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user-read", "user-write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user-read", "user-write"})
     */
    private $surnames;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdDate;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedDate;
    
    public function __construct()
    {
        parent::__construct();
        $this->createdDate = new \dateTime();
        $this->updatedDate = new \dateTime();
    }
    public function __toString()
    {
        return $this->getName() . " " . $this->getSurnames() . " (". $this->getId() . ")";
    }
    public function getId()
    {
        return $this->id;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurnames(): ?string
    {
        return $this->surnames;
    }

    public function setSurnames(?string $surnames): self
    {
        $this->surnames = $surnames;

        return $this;
    }

    /**
     * @Groups({"user-read", "user-write"})
     */
    public function getEmail()
    {
      return $this->email;
    }

    /**
     * @Groups({"user-read", "user-write"})
     */
    public function getUsername()
    {
      return $this->username;
    }

    /**
     * @Groups({"user-read", "user-write"})
     */
    public function setUsername( $username): self
    {
         $this->username = $username;

        return $this;
    }


    /**
     * @Groups({"user-write"})
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
        return $this;
    }

    /**
     * @Groups({"user-read", "user-write"})
     */
    public function getEnabled()
    {
      return $this->enabled;
    }

    /**
     * @Groups({"user-read", "user-write"})
     */
    public function getLastLogin()
    {
      return $this->lastLogin;
    }

    public function getUserRole(): ?string
    {
        return $this->userRole;
    }

    public function setUserRole(?string $userRole)
    {
        $this->userRole = $userRole;
    }

    public function getCreatedDate(): ?\dateTime
    {
        return $this->createdDate;
    }
    public function setCreatedDate(?\dateTime $createdDate = null): self
    {
        $this->createdDate = $createdDate? $createdDate: new \dateTime();
        return $this;
    }

    public function getUpdatedDate(): ?\dateTime
    {
        return $this->updatedDate;
    }
    
    public function setUpdatedDate(?\dateTime $updatedDate = null): self
    {
        $this->updatedDate = $updatedDate? $updatedDate: new \dateTime();
        return $this;
    }

}
