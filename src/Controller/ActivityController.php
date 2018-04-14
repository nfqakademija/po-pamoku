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
        $activities = $this->getDoctrine()->getRepository(Activity::class)->findAll();
        return $this->render('activity/index.html.twig', array('activities' => $activities));
    }

    /**
     * @Route("/activity/edit/{id}", name="activity_club")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        //$test = $this->getDoctrine()->getRepository(Activity::class)->findBySomething($id);
        //var_dump($test);
        return $this->render('activity/edit.html.twig', array());
    }
    /**
     * @Route("/activity/{id}", name="activity_show")
     */
    public function show($id) {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        return $this->render('activity/show.html.twig', array('activity' => $activity));
    }
}