<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.28
 * Time: 19.13
 */

namespace App\Exception;


use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountBlockedException extends AccountStatusException
{
    public function getMessageKey()
    {
        return 'Vartotojas užblokuotas';
    }

}