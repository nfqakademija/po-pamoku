<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.28
 * Time: 00.46
 */

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FormVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof Request) {
            return false;
        }
        
        $path = $subject->getPathInfo();
        
        if ($path !== '/login' && !strpos($path, 'register')) {
            return false;
        }
        
        return true;
    }
    
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        
        if ($user === 'anon.') {
            return true;
        }
        
        return false;
    }
    
}