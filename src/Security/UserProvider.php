<?php

namespace App\Security;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // Try to find user by email first
        $user = $this->userRepository->findOneBy(['email' => $identifier]);

        // If not found by email, try to find by username
        if (!$user) {
            $user = $this->userRepository->findOneBy(['username' => $identifier]);
        }

        if (!$user) {
            throw new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        // Re-fetch the user from the database
        $refreshedUser = $this->userRepository->find($user->getId());

        if (!$refreshedUser) {
            throw new UserNotFoundException('User not found.');
        }

        return $refreshedUser;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }
}
