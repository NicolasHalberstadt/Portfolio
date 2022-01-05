<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use ReCaptcha\ReCaptcha;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * @throws TransportExceptionInterface
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        $google_recaptcha_site_key = $_ENV['GOOGLE_RECAPTCHA_SITE_KEY'];
        
        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($_POST['g-recaptcha-response'])) {
                $secret = $_ENV['GOOGLE_RECAPTCHA_SECRET'];
                $recaptcha = new ReCaptcha($secret);
                $resp = $recaptcha->verify($_POST['g-recaptcha-response']);
                
                if ($resp->isSuccess()) {
                    $contactFormData = $form->getData();
                    $email = (new TemplatedEmail())
                        ->from('halberstadtnicolas@gmail.com')
                        ->to('halberstadtnicolas@gmail.com')
                        ->subject('Nouvelle demande de contact')
                        ->htmlTemplate('contact/alert.html.twig')
                        ->context(
                            [
                                'user' => $contactFormData['nom'],
                                'mail' => $contactFormData['email'],
                                'subject' => $contactFormData['sujet'],
                                'message' => $contactFormData['message'],
                            ]
                        );
                    $mailer->send($email);
                    
                    $this->addFlash('success', 'Votre demande de contact a bien été envoyée.');
                    
                    $email = (new TemplatedEmail())
                        ->from('halberstadtnicolas@gmail.com')
                        ->to($contactFormData['email'])
                        ->subject('Votre demande de contact')
                        ->htmlTemplate('contact/email.html.twig')
                        ->context(
                            [
                                'user' => $contactFormData['nom'],
                            ]
                        );
                    $mailer->send($email);
                    
                    return $this->redirectToRoute('about');
                }
            }
        }
        
        return $this->render(
            'contact/form.html.twig',
            [
                'form' => $form->createView(),
                'google_recaptcha_site_key' => $google_recaptcha_site_key,
            ]
        );
    }
}
