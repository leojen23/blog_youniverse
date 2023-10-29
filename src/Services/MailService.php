<?php

namespace App\Services;

use App\Entity\User;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailService
{
    private MailerInterface $mailer;
    private EmailVerifier $emailVerifier;
 
    public function __construct(MailerInterface $mailer, EmailVerifier $emailVerifier)
        {
            $this->mailer = $mailer;
            $this->emailVerifier = $emailVerifier;
        }

    public function getEmailVerifier(): ?EmailVerifier
    {
        return $this->emailVerifier;
    }

    public function sendVerifyEmail(User $user){

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
        (new TemplatedEmail())
            ->from(new Address('blog@youniverse.com', 'Admin'))
            ->to($user->getEmail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig')
    );
    }
    public function sendAdminConnexionEmail(User $user){

        $email = (new TemplatedEmail())
            ->from('blog@test.com')
            ->to($user->getEmail())
            ->subject('Connexion Administrateur')
            ->text('Bonjour !' . $user->getEmail() . 'vient de se connecter au back office')
            ->htmlTemplate('security/admin_login_email.html.twig')
            ->context([
                'userEmail' => $user->getEmail(),
            ]);

        $this->mailer->send($email);
    }

    public function handleConfirmation(Request $request, User $user){
        $this->emailVerifier->handleEmailConfirmation($request, $user);
    }
}