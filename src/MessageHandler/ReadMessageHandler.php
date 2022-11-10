<?php

namespace App\MessageHandler;

use App\Entity\Metadata;
use App\Message\ReadMessage;
use App\Repository\MessageRepository;
use App\Repository\MetadataRepository;
use App\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Security;

final class ReadMessageHandler implements MessageHandlerInterface
{

    public function __construct(
        Private ThreadRepository $threadRepository,
        Private MessageRepository $messageRepository,
        Private Security $security,
        Private EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(ReadMessage $message)
    {
        $thread = $this->threadRepository->findOneBy(["slug" => $message->getThread()]);

        if($this->security->getUser()){

            foreach ($thread->getMessages() as $m) {
                if($this->messageRepository->findMessageWithMetadata($m->getSlug(),$this->security->getUser()->getSlug()))
                continue;
                
                $m->addMetadata(
                    (new Metadata())
                    ->setMessage($m)
                    ->setUser($this->security->getUser())
                    ->setReadDate(new \DateTime())
                );
                
                $this->entityManager->flush();
            }
        }
        
        // do something with your message
    }
}
