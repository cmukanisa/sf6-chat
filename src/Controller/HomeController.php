<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Message\ReadMessage;
use App\Repository\ThreadRepository;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function __construct(
        Private ThreadRepository $threadRepository,
        Private MessageRepository $messageRepository,
        Private MessageBusInterface $bus
    )
    {
    }

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $thread = $this->threadRepository->findParticipant($this->getUser()->getSlug(),$request->get('thread'));
        if($thread)
            $this->bus->dispatch(new ReadMessage($thread->getSlug()));

        // CreatedMessage
        // ReadMessage
        // Update Metadata
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message
                ->setSender($this->getUser())
                ->setSenderText($this->getUser()->getFullname())
                ->setThread($thread)
            ;
            $this->messageRepository->save($message, true);

            $this->addFlash('notice','Le message a été envoyé avec succès !');
            return $this->redirectToRoute('home', ["thread" => $thread->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/index.html.twig', [
            'threads'   => $this->threadRepository->findAll(),
            'thread'    => $thread,
            
            'message'   => $message,
            'form'      => $form,
        ]);
    }
}
