<?php

namespace App\Security;

use App\Exception\AccountBlockedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BannedUserChecker implements UserCheckerInterface
{
   public function checkPreAuth(UserInterface $user)
   {
       if (!$user instanceof UserInterface) {
           return;
       }

       if ($user->getIsBlocked()) {
           throw new AccountBlockedException('Vartotojas užblokuotas');
       }
   }

   public function checkPostAuth(UserInterface $user)
   {
       if (!$user instanceof UserInterface) {
           return;
       }

       if ($user->getIsBlocked()) {
           throw new AccountBlockedException('Vartotojas užblokuotas');
       }
   }
}