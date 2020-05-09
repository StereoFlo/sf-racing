<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use function serialize;

class AuthController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepo;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        UserRepository $userRepo,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepo        = $userRepo;
        $this->tokenStorage    = $tokenStorage;
        $this->session         = $session;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/login", methods={"GET"}, name="login_form")
     */
    public function loginForm(): Response
    {
        return $this->render('auth/login.html.twig');
    }

    /**
     * @Route("/login", methods={"POST"}, name="login_process")
     */
    public function loginProcess(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if (!$this->getUser()) {
            $username = $request->get('username');
            $password = $request->get('password');

            $user = $this->userRepo->getByUsername($username);

            if (empty($user)) {
                throw new Exception('user does not found');
            }

            if (!$passwordEncoder->isPasswordValid($user, $password)) {
                throw new Exception('password is not valid');
            }

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);
            $this->session->set('_security_main', serialize($token));
            $this->eventDispatcher->dispatch(new InteractiveLoginEvent($request, $token));

            return $this->redirect('/');
        }

        return $this->redirect('/');
    }

    /**
     * @Route("/register", methods={"GET"}, name="register_form")
     */
    public function registerForm(): Response
    {
        return $this->render('auth/register.html.twig');
    }

    /**
     * @Route("/register", methods={"POST"}, name="register_process")
     */
    public function registerProcess(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $email    = $request->get('email');
        $password = $request->get('password');
        $username = $request->get('username');

        $user = new User('', '', '');
        $user = new User($email, $passwordEncoder->encodePassword($user, $password), $username);

        $this->userRepo->save($user);

        return $this->redirectToRoute('login_form');
    }
}
