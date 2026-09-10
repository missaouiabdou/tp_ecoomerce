<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserRegistrationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    ) {
    }

    public function registerUser(User $user, string $plainPassword): User
    {
        // VULNERABILITY — password stored as unsalted MD5 (fast, crackable,
        // no salt) so the raw-SQL login can compare against it.
        $user->setPassword(md5($plainPassword));
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function userExists(string $email): bool
    {
        return $this->userRepository->findByEmail($email) !== null;
    }
}
