<?php


namespace App\Controller;


use App\Form\RegistrationType;
use App\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="registration")
     */
    public function registrationAction(Request $request, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $formAuthenticator)
    {
        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($userData);
            $em->flush();

            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                    $userData,
                    $request,
                    $formAuthenticator,
                    'main'
                );
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}