<?php

namespace App\Security\Voter;

use App\Entity\Duck;
use App\Entity\Quack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

class QuackVoter extends Voter
{

    const DELETE = 'delete';
    const EDIT = 'edit';

    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::DELETE, self::EDIT])) {
            return false;
        }

        // only vote on `Quack` objects
        if (!$subject instanceof Quack) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof Duck) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Quack object, thanks to `supports()`
        /** @var Quack $quack */
        $quack = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($quack, $user);
            case self::EDIT:
                return $this->canEdit($quack, $user);
        }

        throw new \LogicException('Access denied');
    }

    private function canDelete(Quack $quack, Duck $user)
    {

        return $user === $quack->getAuteur();
    }

    private function canEdit(Quack $quack, Duck $user)
    {
        // this assumes that the Quack object has a `getAuthor()` method
        return $user === $quack->getAuteur();
    }

}