<?php
namespace App\Controller;

use App\Entity\Activity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ActivityController extends Controller {

    /**
     * @Route("/", name="activity_list")
     * @Method({"GET"})
     */
    public function index() {

        return $this->render('activity/index.html.twig');
    }

    /**
     * @Route("/activity/{id}", name="activity_show")
     */
    public function show($id) {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        return $this->render('activity/show.html.twig', array('activity' => $activity));
    }
}