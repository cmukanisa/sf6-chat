<?php

namespace App\Entity;

use App\Entity\Message;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ThreadRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\Query;
use App\Resolver\MessageMutationResolver;
use ApiPlatform\Metadata\GraphQl\Mutation;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Metadata\GraphQl\QueryCollection;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
#[ApiResource(
    graphQlOperations: [
        new Mutation(
            name: 'create',
            // security: "is_granted('ROLE_USER')",
            // resolver: MessageMutationResolver::class
        ),
        new Mutation(name: 'update'),
        // new Query(security: "is_granted('THREAD_READ',object)"),
        new Query(),
        new QueryCollection(),
        // new QueryCollection(security: "is_granted('ROLE_ADMIN')"),
    ],
    operations: [
        new GetCollection(),
        // new GetCollection(security: "is_granted('ROLE_USER')"),
        new Get(),
        // new Get(security: "is_granted('THREAD_READ',object)"),
    ],
)]
#[ORM\HasLifecycleCallbacks()]
class Thread
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\OneToMany(mappedBy: 'thread', targetEntity: Message::class, cascade:["persist"])]
    private Collection $messages;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'threads')]
    private Collection $participants;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setThread($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getThread() === $this) {
                $message->setThread(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->addThread($this);
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeThread($this);
        }

        return $this;
    }
}
