<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class GlobalVoter extends Voter
{
    const POST = 'POST';
    const DELETE = 'DELETE';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const GET = 'GET';

    private $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::POST, self::DELETE, self::PUT, self::PATCH, self::GET])) {
            return false;
        }

        if (
            !$subject instanceof Product ||
            !$subject instanceof Order ||
            !$subject instanceof Cart ||
            !$subject instanceof Address ||
            !$subject instanceof User
        ) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Product|Order|Cart|Address|User $entity */
        $entity = $subject;

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return $this->isOwner($entity, $user);
    }

    private function isOwner($entity, User $user): bool
    {
        switch (get_class($user)) {
            case "Product":
                return $user === $entity->submittedBy;
            case "Order":
            case "Address":
                return $user === $entity->associatedUser;
            case "Cart":
                return $user === $entity->relatedUser;
            case "User":
                return $user === $entity;
        }

        throw new \LogicException('This code should not be reached!');
    }
}
