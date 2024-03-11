<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Form\Profil1Type;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/profil/crud')]
class ProfilCrudController extends AbstractController
{
    #[Route('/', name: 'app_profil_crud_index', methods: ['GET'])]
    public function index(ProfilRepository $profilRepository): Response
    {
        return $this->render('profil_crud/index.html.twig', [
            'profils' => $profilRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_profil_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $profil = new Profil();
        $form = $this->createForm(Profil1Type::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($profil);
            $entityManager->flush();

            return $this->redirectToRoute('app_profil_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profil_crud/new.html.twig', [
            'profil' => $profil,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profil_crud_show', methods: ['GET'])]
    public function show(Profil $profil): Response
    {
        return $this->render('profil_crud/show.html.twig', [
            'profil' => $profil,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profil_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Profil $profil, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Profil1Type::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profil_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profil_crud/edit.html.twig', [
            'profil' => $profil,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profil_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Profil $profil, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$profil->getId(), $request->request->get('_token'))) {
            $entityManager->remove($profil);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profil_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
