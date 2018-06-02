<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\User;
use App\Form\Type\ActivityType;
use App\Form\Type\LocationType;
use App\Form\Type\RegistrationType;
use App\Utils\Utils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register/{role}", name="registration")
     */
    public function registrationAction(
        $role,
        Request $request,
        SessionInterface $session
    ) {
        $redirect = $session->get('redirect');
        if (!$redirect) {
            $session->set('redirect', $request->headers->get('referer'));
        }

        switch ($role) {
            case 'owner':
                return $this->ownerRegistration($request, $session);
            case 'user':
                return $this->userRegistration($request, $session);
            default:
                throw $this->createNotFoundException('Tokio puslapio nėra');
        }
    }

    private function userRegistration(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();

            return $this->addNewUserToDatabaseAndAuthenticate($request, $userData);
        }

        return $this->getForm($form);
    }

    private function ownerRegistration(Request $request, SessionInterface $session)
    {
        if ($request->request->get('activity')) {
            return $this->ownerRegistrationSecondStep($request, $session);
        }

        if ($request->request->get('location')) {
            return $this->ownerRegistrationLastStep($request, $session);
        }

        $session = $this->refreshSession($session);

        $userForm = $this->createForm(RegistrationType::class, $session->get('user'));
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $userData = $userForm->getData();
            $session->set('user', $userData);
            $session->set('previousStep', '1');

            return $this->ownerRegistrationSecondStep($request, $session);
        }
        $session->remove('previousStep');
        return $this->getForm($userForm);
    }

    private function ownerRegistrationSecondStep(Request $request, SessionInterface $session)
    {
        $activity = $this->getActivityFromSession($session);
        $activityForm = $this->createForm(ActivityType::class, $activity);
        $activityForm->handleRequest($request);
        if ($activityForm->isSubmitted() && $activityForm->isValid()) {
            $activityData = $activityForm->getData();
            $activityData = $this->handleImage($activityData);
            $session->set('activity', $activityData);
            $session->set('previousStep', '2');
            dump($session->get('previousStep'));
            if ($activityForm->get('back')->isClicked()) {
                return $this->backToPreviousStep(RegistrationType::class, $session, 'user');
            }

            return $this->ownerRegistrationLastStep($request, $session);
        }
        $session->remove('previousStep');
        return $this->getForm($activityForm);
    }

    private function ownerRegistrationLastStep(Request $request, SessionInterface $session)
    {
        $activity = $this->getActivityFromSession($session);
        $locationForm = $this->createForm(LocationType::class, $session->get('location'));
        $locationForm->handleRequest($request);

        if ($locationForm->isSubmitted() && $locationForm->isValid()) {
            $locationData = $locationForm->getData();
            $session->set('location', $locationData);
            $session->set('previousStep', '3');
            if ($locationForm->get('back')->isClicked()) {
                return $this->backToPreviousStep(ActivityType::class, $session, 'activity');
            }

            $user = $session->get('user');
            $activity = $session->get('activity');
            $activity->setLocation($session->get('location'));
            $user->setActivity($activity);
            return $this->addNewUserToDatabaseAndAuthenticate($request, $user);
        }

        $session->remove('previousStep');
        return $this->getForm($locationForm);
    }

    /**
     * @param Request $request
     * @param User $userData
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    private function addNewUserToDatabaseAndAuthenticate(Request $request, User $userData)
    {
        $em = $this->getDoctrine()->getManager();
        $userData->setIsBlocked(false);
        $em->persist($userData);
        $em->flush();

        return $this->get('security.authentication.guard_handler')
            ->authenticateUserAndHandleSuccess(
                $userData,
                $request,
                $this->get('form_authenticator'),
                'main'
            );
    }

    /**
     * @param SessionInterface $session
     * @return Activity
     */
    private function getActivityFromSession(SessionInterface $session): ?Activity
    {
        $activity = $session->get('activity');

        if ($activity) {
            $subcategory = $activity->getSubcategory();
            $subcategory = $this->getDoctrine()->getManager()->merge($subcategory);
            $timetables = $activity->getTimetables();
            $newTimetable = [];
            foreach ($timetables as $timetable) {
                $weekday = $timetable->getWeekday();
                $weekday = $this->getDoctrine()->getManager()->merge($weekday);
                $timetable->setWeekday($weekday);
                $newTimetable[] = $timetable;
            }
            $activity->setTimetables($newTimetable);
            $activity->setSubcategory($subcategory);
        } else {
            $activity = new Activity();
        }

        return $activity;
    }

    /**
     * @param Activity $activity
     * @return Activity
     */
    private function handleImage(Activity $activity): Activity
    {

        $file = $activity->getPathToLogo();
        if ($file) {
            $fileName = Utils::generateUniqueFileName() . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('uploads_directory'),
                $fileName
            );
            $activity->setPathToLogo('/uploads/' . $fileName);
        }

        return $activity;
    }

    /**
     * @param FormInterface $form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function getForm(FormInterface $form)
    {
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param string $backType
     * @param SessionInterface $session
     * @param string $backName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function backToPreviousStep(string $backType, SessionInterface $session, string $backName)
    {
        $data = $session->get($backName);
        if ($data instanceof Activity) {
            $data = $this->handleImage($data);
        }

        $backForm = $this->createForm($backType, $data);

        return $this->getForm($backForm);
    }

    /**
     * @param SessionInterface $session
     * @return SessionInterface
     */
    private function refreshSession(SessionInterface $session): SessionInterface
    {
        if ($session->get('previousStep')) {
            return $session;
        }

        $session->remove('user');
        $session->remove('activity');
        $session->remove('location');
        dump(1);
        return $session;
    }
}