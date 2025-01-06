<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index()
    {
        $form = $this->createForm(ContactType::class);

        return $this->render('main/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contact', name: 'app_contact', methods: ['POST'])]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->from($data['email'])
                ->to('contact@nicolashalberstadt.com')
                ->subject('Nouvelle demande de contact')
                ->text("Message : " . $data["message"]);

            $mailer->send($email);

            $message = "J'ai bien reçu votre demande de contact pour la création d'un site " . $data["type"] . " et je reviendrai vers vous dans les plus brefs délais.";

            return $this->json(['status' => 'success', 'message' => $message]);
        }

        $errors = [];
        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()][] = (string) str_replace('ERROR:', '', $child->getErrors(false));
            }
        }

        return $this->json(['status' => 'error', 'errors' => $errors]);
    }
}
