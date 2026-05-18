<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository
    ) {
    }

    public function registerUser(User $user, string $plainPassword): User
    {
        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        // Persist and flush
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function userExists(string $email): bool
    {
        return $this->userRepository->findByEmail($email) !== null;
    }
}
