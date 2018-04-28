<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\Model\ChangePasswordModel;
use App\Form\ProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     */
    public function showProfile()
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('viewProfile', $user);

        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $profile = $userRepo->findProfileInformation($user->getId());
        
        $profileVars = [
            'Vardas' => $profile[0]['name'],
            'Pavardė' => $profile[0]['surname'],
            'Elektroninis paštas' => $profile[0]['email'],
            'Telefono numeris' => $profile[0]['phone'],
            'Būrelis' => $profile[0]['activity'],
        ];
        
        return $this->render('profile/index.html.twig', [
            'profileVars' => $profileVars,
        ]);
    }
    
    /**
     * @Route("/profile/edit", name="profile_edit")
     */
    public function editProfile(Request $request)
    {
        $user = $this->getUser();
        
        $this->denyAccessUnlessGranted('editProfile', $user);
        
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            
            return $this->redirectToRoute('profile');
        }
        
        return $this->render('profile/edit.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/profile/changepassword", name="change_password")
     */
    public function changePassword(Request $request)
    {
        $user = $this->getUser();
        
        $this->denyAccessUnlessGranted('changePassword', $user);
        
        $changePassword = new ChangePasswordModel();
        $form = $this->createForm(ChangePasswordType::class, $changePassword);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $user->setPlainPassword($data->getNewPassword());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            return $this->redirectToRoute('profile');
        }
        
        return $this->render('profile/changePassword.html.twig', [
            'passwordForm' => $form->createView(),
        ]);
    }
}
