<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Utils\Utils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\ActivityType;

class ActivityEditController extends Controller
{
    
    /**
     * @Route("/activity/edit/{id}", name="activity_edit")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        
        $this->denyAccessUnlessGranted('edit', $activity);
        
        $form = $this->createForm(ActivityType::class, $activity);
        $pathToLogo = $activity->getPathToLogo();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();
            $file = $userData->getPathToLogo();
            
            if (isset($file)) {
                $fileName = Utils::generateUniqueFileName() . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('uploads_directory'),
                    $fileName
                );
                $userData->setPathToLogo($fileName);
            } else {
                $userData->setPathToLogo($pathToLogo);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userData);
            $entityManager->flush();
            return $this->redirectToRoute('activity_list');
        }
        return $this->render('admin/activity/edit.html.twig', [
            'form' => $form->createView(),
            'pathToLogo' => $pathToLogo,
        ]);
    }
}