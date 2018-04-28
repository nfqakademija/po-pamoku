<?php

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;


class ChangePasswordModel
{

    /**
     * @SecurityAssert\UserPassword(
     *     message = "Įvestas neteisingas slaptažodis."
     * )
     */
    protected $oldPassword;

    /**
     * @Assert\NotBlank()
     */
    protected $newPassword;

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    public function getNewPassword()
    {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }
}