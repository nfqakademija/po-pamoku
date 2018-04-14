<?php
namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Location;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Form\Type\ActivityType;

class AdminController extends Controller {

    /**
     * @Route("/admin/activity/edit/{id}", name="activity_edit")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);

        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userData);
            $entityManager->flush();
            return $this->redirectToRoute('activity_list');
        }
        return $this->render('admin/activity/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}