<?php
declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class MemberstackUser implements UserInterface
{
    public function __construct(
        private readonly array $member,
    ) {
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->member['data']["auth"]["email"];
    }
}