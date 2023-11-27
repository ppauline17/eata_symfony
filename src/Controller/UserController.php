<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'crud_user' => true
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, $id): Response
    {
        $onlineUser = $this->getUser();
        if ($onlineUser->getId() != $id && !($this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException('Accès non autorisé.');
        }
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, $id): Response
    {
        $onlineUser = $this->getUser();
        if ($onlineUser->getId() != $id && !($this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException('Accès non autorisé.');
        }
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($newPassword = $form['newPassword']->getData()){
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $newPassword
                    )
                );
            }
            if($lastName = $form['lastname']->getData()){
                $user->setLastname(mb_strtoupper($lastName));
            }
            $user->setFirstname(ucfirst(strtolower($form['firstname']->getData())));
            $entityManager->flush();

            return $this->redirectToRoute('app_user_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, $id, ArticleRepository $articleRepository): Response
    {
        $onlineUser = $this->getUser();
        if ($onlineUser->getId() == $id){$logout = true;}
        if ($onlineUser->getId() != $id && !($this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException('Accès non autorisé.');
        }

        $articles = $articleRepository->findBy(['user' => $id]);
        foreach ($articles as $article) {
            $article->setUser(null);
            $entityManager->flush();
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        if($logout){
            return $this->redirectToRoute('app_page_home', [], Response::HTTP_SEE_OTHER);
        }else{
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
