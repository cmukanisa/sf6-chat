<?php

namespace App\Entity;

use App\Entity\Metadata;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\MessageRepository;
use Doctrine\ORM\Query\AST\UpdateItem;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\Mutation;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Metadata\GraphQl\QueryCollection;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks()]

#[ApiResource(
    graphQlOperations: [
        new Mutation(
            name: 'create',
        ),
        new Mutation(name: 'update'),
        new Query(),
        // new Query(security: "is_granted('MESSAGE_READ',object)"),
        new QueryCollection(),
        // new QueryCollection(security: "is_granted('ROLE_USER')"),
    ]
)]
class Message
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable:false)]
    private ?User $sender = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable:false)]
    private ?Thread $thread = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: Metadata::class, orphanRemoval:true)]
    private Collection $metadata;


    #[ORM\Column(length: 255, nullable:true)]
    private ?string $senderText = null;

    public function __construct()
    {
        $this->metadata = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    public function setThread(?Thread $thread): self
    {
        $this->thread = $thread;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Metadata>
     */
    public function getMetadata(): Collection
    {
        return $this->metadata;
    }

    public function addMetadata(Metadata $metadata): self
    {
        if (!$this->metadata->contains($metadata)) {
            $this->metadata->add($metadata);
            $metadata->setMessage($this);
        }

        return $this;
    }

    public function removeMetadata(Metadata $metadata): self
    {
        if ($this->metadata->removeElement($metadata)) {
            // set the owning side to null (unless already changed)
            if ($metadata->getMessage() === $this) {
                $metadata->setMessage(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of senderText
     *
     * @return ?string
     */
    public function getSenderText(): ?string
    {
        return $this->senderText;
    }

    /**
     * Set the value of senderText
     *
     * @param ?string $senderText
     *
     * @return self
     */
    public function setSenderText(?string $senderText): self
    {
        $this->senderText = $senderText;

        return $this;
    }
}
