<?php

namespace App\Form\Type;


use App\Entity\Activity;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\ActivityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentType extends AbstractType
{
    private $requestStack;
    private $ar;
    private $tokenStorage;

    public function __construct(RequestStack $requestStack, ActivityRepository $ar, TokenStorageInterface $tokenStorage)
    {
        $this->requestStack = $requestStack;
        $this->ar = $ar;
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentText')
            ->add('user', HiddenType::class, [
                'data_class' => User::class
            ])
            ->add('activity', HiddenType::class, [
                'data_class' => Activity::class
            ])
        ;

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $comment = $event->getData();
                $id = $this->requestStack->getCurrentRequest()->get('id');
                $activity = $this->ar->find($id);
                $user = $this->tokenStorage->getToken()->getUser();
                $comment->setUser($user);
                $comment->setActivity($activity);
                $event->setData($comment);
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class
        ]);
    }
}