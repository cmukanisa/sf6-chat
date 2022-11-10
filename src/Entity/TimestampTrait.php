<?php

namespace App\Entity;

use Symfony\Polyfill\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;

trait TimestampTrait
{
    #[ORM\Column(type: 'datetime', nullable:true)]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable:true)]
    private $updatedAt;


    #[ORM\Column(type: 'datetime', nullable:true)]
    private $deletedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $slug;

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist()]
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    /**
     * Get the value of updatedAt
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate()]
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * Get the value of deletedAt
     */
    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    #[ORM\PreRemove()]
    public function setDeletedAt()
    {
        $this->deletedAt =  new \DateTime();

        return $this;
    }

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    #[ORM\PrePersist()]
    public function setSlug()
    {
        $uuid = new Uuid();
        $this->slug = $uuid->uuid_create(UUID_TYPE_RANDOM);
        return $this;
    }
}