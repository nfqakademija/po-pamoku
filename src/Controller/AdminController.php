<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.28
 * Time: 20.52
 */

namespace App\Controller;


use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
//    /**
//     * @Route("/a/createadmin", name="admin_create")
//     */
//    public function createAdmin()
//    {
//        $em = $this->getDoctrine()->getManager();
//        $admin = new User();
//        $admin->setPlainPassword('admin');
//        $admin->setEmail('admin@admin.com');
//        $admin->setIsBlocked(false);
//        $admin->setName('Admin');
//        $admin->setSurname('Admin');
//        $admin->setPhoneNumber('87456321');
//        $admin->setRole('ROLE_ADMIN');
//
//        $em->persist($admin);
//        $em->flush();
//
//        return new JsonResponse('');
//    }


    /**
     * @Route("/admin/profile", name="admin_profile")
     */
    public function adminProfileAction(){
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/userlist", name="admin_user_list")
     */
    public function userListAction()
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $users = $em->findAllUsersForAdmin();

        return $this->render('admin/users.html.twig', ['users' => $users]);

    }

    /**
     * @Route("/admin/user/profile/{id}", name="admin_user_profile_action")
     */
    public function userProfileAction(Request $request, $id)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->findOneUserForAdmin($id);

        $form = $this->createFormBuilder()
            ->add('blockAction', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clicked = $form->get('blockAction')->isClicked();
            $em = $this->getDoctrine()->getManager();
            $userObj = $repo->find($id);
            if ($clicked) {
                $userObj->setIsBlocked(!$userObj->getIsBlocked());
                $em->persist($userObj);
                $em->flush();
                return $this->redirect($request->getUri());
            }
        }

        return $this->render('admin/userprofile.html.twig', ['user' => $user[0], 'form' => $form->createView()]);
    }

}