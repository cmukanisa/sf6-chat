<?php

namespace App\Controller;

use App\Repository\ThreadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function __construct(
        Private ThreadRepository $threadRepository
    )
    {
    }

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        // dd($this->threadRepository->findAll());
        $thread = $this->threadRepository->findOneBy(["slug" => $request->get('thread')]);
        // dump($thread);
        return $this->render('home/index.html.twig', [
            'threads'   => $this->threadRepository->findAll(),
            'thread'    => $thread
        ]);
    }
}
