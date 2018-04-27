<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.27
 * Time: 18.29
 */

namespace App\Security;


use App\Entity\Activity;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        if ($attribute !== 'edit') {
            return false;
        }

        if (!$subject instanceof Activity) {

            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {

            return false;
        }

        switch ($attribute) {
            case 'edit':
                return $this->canEdit($subject, $user);
        }
    }

    private function canEdit(Activity $activity, User $user)
    {
        if ($user->getId() === $activity->getUser()->getId()) {
            return true;
        }
    }

}