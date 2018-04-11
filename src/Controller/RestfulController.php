<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.9
 * Time: 10.45
 */

namespace App\Controller;


use App\Entity\Activity;
use App\Form\ActivityType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Limenius\Liform\Liform;
use Limenius\Liform\Resolver;
use Limenius\Liform\Transformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;


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
     * @Rest\Get("/api/form")
     */

    public function getForm()
    {
        $s = new Activity();

        $form = $this->createForm(ActivityType::class, $s);
        $serializer = $this->get('jms_serializer');
        $response = $serializer->serialize($form->createView(), 'json');

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
