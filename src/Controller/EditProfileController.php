<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class EditProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="user_profile")
     */
    public function edit(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        if($currentUser->getId() == $user->getId())
        {
            $form = $this->createForm(EditProfileType::class, $user);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('newPassword')->getData()
                    )
                );
                $user = $form->getData();
    
                $entityManager->persist($user);
                $entityManager->flush();
    
                // do anything else you need here, like send an email
    
                return $this->redirectToRoute('profile', ['id' => $this->getUser()->getId()]);
            }
    
            return $this->render('profile/profile.html.twig', [
                'EditProfileForm' => $form->createView(),
            ]);
        }
        else
        {
            return $this->redirectToRoute('index');
        }
    }

     /**
     * @Route("/profile/unsubscribe/{id}", methods={"DELETE"})
     * 
     */
    public function unsubscribe(Request $request, $id, TokenStorageInterface $tokenStorage)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if(!$user)
        {
            throw $this->createNotFoundException('No user found with the id : ' . $id);
        }
        
        $entityManager->remove($user);  //Supprime l'entité user concernée
        $entityManager->flush();

        $session = $this->get('session'); 
        $tokenStorage->setToken(null);
        
        $session->invalidate(); // Supprime les infos de la session

        $response = new Response();
        $response->send();
    }

    public function profile_index(Request $request) : Response
    {
        return $this->redirectToRoute('profile', ['id' => $this->getUser()->getId()]);
    }
}
