<?php

namespace App\Controller;

use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class ChatController extends AbstractController
{
    #[Route('/', name: 'app_chat', methods:['GET', 'POST'])]
    public function index(Request $request, HubInterface $hub): Response
    {
        $form = $this->createForm(MessageType::class);
        $emptyForm = clone $form; // clona o formulário para ser adicionado depois do envio do post

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            // dd($data)

            $hub->publish(new Update(
                'chat',
                $this->renderView('chat/message.stream.html.twig', ['message' => $data['message']])
            ));

            $form = $emptyForm;
        }

        return $this->render('chat/_index.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/publish', name: 'publish')]
    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            'https://example.com/books/1',
            json_encode(['status' => 'OutOfStock'])
        );

        $hub->publish($update);

        return new Response('published!');
    }
}
