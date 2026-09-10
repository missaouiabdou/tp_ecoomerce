<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\UserRegistrationService;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class AuthController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserRegistrationService $userRegistrationService): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('plainPassword')->getData();

            if ($userRegistrationService->userExists($user->getEmail())) {
                $this->addFlash('error', 'This email is already registered.');

                return $this->redirectToRoute('register');
            }


            $userRegistrationService->registerUser($user, $plainPassword);

            $this->addFlash('success', 'Registration successful! Please log in.');

            return $this->redirectToRoute('login');
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(
        Request $request,
        AuthenticationUtils $authenticationUtils,
        Connection $connection,
        TokenStorageInterface $tokenStorage,
        UserRepository $userRepository
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('browse_categories');
        }

        if ($request->isMethod('POST')) {
            $email = (string) $request->request->get('email', '');
            $password = (string) $request->request->get('password', '');

            // VULNERABILITY — SQL injection: the raw user input is
            // concatenated directly into the SQL query instead of being
            // bound as a parameter. A payload like
            //   ' OR 1=1 --
            // in the email field comments out the password check and logs
            // in as the first user in the table.
            $sql = "SELECT * FROM users WHERE email = '".$email."'"
                ." AND password = '".md5($password)."' LIMIT 1";

            $row = $connection->fetchAssociative($sql);

            if (false !== $row && null !== $user = $userRepository->find($row['id'])) {
                // Log the user in manually — no credential verification
                // actually happened above.
                $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
                $tokenStorage->setToken($token);
                $request->getSession()->set('_security_main', serialize($token));

                return $this->redirectToRoute('browse_categories');
            }

            $request->getSession()->set(
                SecurityRequestAttributes::AUTHENTICATION_ERROR,
                new CustomUserMessageAuthenticationException('Invalid credentials.')
            );
            $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

            return $this->redirectToRoute('login');
        }

        return $this->render('auth/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {

    }
}
