<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
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

    /**
     * @Route("/admin/user/{id}/comments/", name="admin_user_comments_action")
     */
    public function userCommentsAction($id)
    {
        $repo = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $repo->findAllCommentsByUserId($id);



        return $this->render('admin/comments.html.twig', [
            'comments' => $comments,
            'user' => $id,
            'form' => $this->createDeleteForm()->createView()]);
    }

    /**
     * @Route("/admin/user/{user}/comments/delete/{id}", name="admin_delete_comment_action")
     * @Method("DELETE")
     */
    public function deleteCommentsAction(Request $request, $id, $user)
    {
        $repo = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $repo->find($id);
        $form = $this->createDeleteForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
        }

        return $this->redirectToRoute('admin_user_comments_action', ['id'=> $user]);

    }

    private function createDeleteForm()
    {
        return $this->createFormBuilder()
                    ->setMethod('DELETE')
                    ->getForm();
    }

}