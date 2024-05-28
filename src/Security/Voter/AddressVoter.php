<?php

namespace App\Security\Voter;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AddressVoter extends Voter
{
    public const UPDATE = 'ADDRESS_UPDATE';
    public const VIEW = 'ADDRESS_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        
        return in_array($attribute, [self::UPDATE, self::VIEW])
            && $subject instanceof \App\Entity\Address;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        if ( !$subject instanceof Address) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::UPDATE:
                // logic to determine if the user can EDIT
                // return true or false
                return $subject->getClient()->getId() === $user->getId();
                break;

            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
