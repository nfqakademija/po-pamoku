<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $index = $client->request('GET', '/');
        $loginLink = $index
            ->filter('a:contains("Prisijungti")')
            ->link();
        ;
        $loginPage = $client->click($loginLink);
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $loginButton = $loginPage->selectButton('Prisijungti');
        $loginForm = $loginButton->form([
            'login[_username]' => 'petrasvalaitis@email.com',
            'login[_password]' => 'password'
        ]);

        $loginSubmit = $client->submit($loginForm);
        echo $client->getResponse()->getContent();
        $redirect = $client->followRedirect();
        $this->assertContains('Atsijungti', $client->getResponse()->getContent());

        $logoutLink = $redirect
            ->filter('a:contains("Atsijungti")')
            ->link();
        $logout = $client->click($logoutLink);

        $redirect = $client->followRedirect();
        $this->assertContains('Prisijungti', $client->getResponse()->getContent());

    }
}
