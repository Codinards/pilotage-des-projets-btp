<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RoleVoter extends Voter
{
    public const USER = 'IS_USER';
    public const ADMIN = 'IS_ADMIN';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::USER, self::ADMIN]);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($user->isLocked()) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::USER:
                return $user->getRole()->getTitle() === "ROLE_USER" || $user->getRole()->getTitle() === "ROLE_ADMIN";
            case self::ADMIN:
                return $user->getRole()->getTitle() === "ROLE_ADMIN";
        }

        return false;
    }
}
