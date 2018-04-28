<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.28
 * Time: 10.12
 */

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProfileVoter extends Voter
{
    const VIEW = 'viewProfile';
    const EDIT = 'editProfile';
    const PWD = 'changePassword';
    
    private $requestStack;
    
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    protected function supports($attribute, $subject)
    {
        $request = $this->requestStack->getCurrentRequest();
        
        $path = $request->getPathInfo();
        
        if (!strpos($path, 'profile')) {
            return false;
        }
        
        if ($attribute !== self::EDIT && $attribute !== self::VIEW && $attribute !== self::PWD) {
            return false;
        }
        
        if (!($subject instanceof User)) {
            return false;
        }
        
        return true;
    }
    
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        
        if (!($user instanceof User)) {
            return false;
        }
        
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($user, $subject);
            case self::VIEW:
                return $this->canView($user, $subject);
            case self::PWD:
                return $this->canEdit($user, $subject);
        }
        
        return false;
    }
    
    private function canEdit($user, $profileUser)
    {
        return $user === $profileUser;
    }
    
    private function canView($user, $profileUser)
    {
        return $this->canEdit($user, $profileUser);
    }
    
    
}