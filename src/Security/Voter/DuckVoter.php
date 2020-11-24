<?php

namespace App\Security\Voter;

use App\Entity\Duck;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class DuckVoter extends Voter
{
    const DELETE = 'delete';
    const EDIT = 'edit';

    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::DELETE, self::EDIT])) {
            return false;
        }

        // only vote on `Duck` objects
        if (!$subject instanceof Duck) {
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
        if ($token->getUser() != $subject) {
            return false;
        }
        // you know $subject is a Duck object, thanks to `supports()`
        /** @var Duck $duck*/
        $duck = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($duck, $user);
            case self::EDIT:
                return $this->canEdit($duck, $user);
        }

        throw new \LogicException('Access denied');
    }

    private function canDelete(Duck $duck, Duck $user)
    {

        return $user === $duck;
    }

    private function canEdit(Duck $duck, Duck $user)
    {
        // this assumes that the Quack object has a `getAuthor()` method
        return $user === $duck;
    }

}
