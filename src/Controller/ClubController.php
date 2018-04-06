<?php
namespace App\Controller;
use App\Entity\Club;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClubController extends Controller {

    /**
     * @Route("/", name="club_list")
     * @Method({"GET"})
     */
    public function index() {
        $clubs = $this->getDoctrine()->getRepository(Club::class)->findAll();
        return $this->render('clubs/index.html.twig', array('clubs' => $clubs));
    }

    /**
     * @Route("/club/edit/{id}", name="edit_club")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);

        return $this->render('clubs/edit.html.twig', array());
    }
    /**
     * @Route("/club/{id}", name="club_show")
     */
    public function show($id) {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);
        return $this->render('clubs/show.html.twig', array('club' => $club));
    }
}