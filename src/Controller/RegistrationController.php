<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\Type\RegistrationType;
use App\Security\LoginFormAuthenticator;
use App\Utils\Utils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends Controller
{
    /**
     * @Route("/register/{role}", name="registration")
     */
    public function registrationAction(
        $role,
        Request $request,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        LoginFormAuthenticator $formAuthenticator
    ) {
        $this->denyAccessUnlessGranted('edit', $request);
        
        $form = $this->createForm(RegistrationType::class);
        
        $form->handleRequest($request);
        
        $em = $this->getDoctrine()->getManager();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();
            
            if ($role === 'owner') {
                $file = $userData->getActivity()->getPathToLogo();
                if ($file) {
                    $fileName = Utils::generateUniqueFileName() . '.' . $file->guessExtension();
                    $file->move(
                        $this->getParameter('uploads_directory'),
                        $fileName
                    );

                    $userData->getActivity()->setPathToLogo('/uploads/' .$fileName);
                }
                
                $location = $userData->getActivity()->getLocation();
                
                $locationFound = $em->getRepository(Location::class)->findLocationByLocation($location);
                
                if ($locationFound) {
                    $userData->getActivity()->setLocation($locationFound);
                }
            }

            $userData->setIsBlocked(false);
            
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
            'form' => $form->createView(),
        ]);
    }
}