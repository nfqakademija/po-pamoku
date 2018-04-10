<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.9
 * Time: 10.45
 */

namespace App\Controller;


use App\Entity\Activity;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class RestfulController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @Rest\Get("/api/activities/{id}")
     */
    public function getAction($id)
    {
        $b = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        $serializer = $this->get('jms_serializer');
        $response = $serializer->serialize($b, 'json');

        return new Response($response);
    }

    /**
     * @Rest\Get("/api/activities")
     * @Template()
     */
    public function cgetAction()
    {
        $b = $this->getDoctrine()->getRepository(Activity::class)->findAll();
        $serializer = $this->get('jms_serializer');
        $response = $serializer->serialize($b, 'json');

        return new Response($response);
    }

    /**
     * @Route("/{reactRouting}", name="index", requirements={"reactRouting"="^(?!api).+"}, defaults={"reactRouting": null})
     */
    public function getIndex()
    {
        return $this->render('activity/testindex.html.twig');
    }

}
