<?php
declare(strict_types=1);

namespace App\Security;

use App\Service\MemberstackService;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class MemberstackUserProvider implements UserProviderInterface
{
    public function __construct(private readonly MemberstackService $memberstackService)
    {
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof MemberstackUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $user;
    }

    public function supportsClass($class)
    {
        return MemberstackUser::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $userData = $this->memberstackService->getUserDetails($identifier);

        if ($userData) {
            return new MemberstackUser($userData);
        }

        throw new UserNotFoundException();
    }
}