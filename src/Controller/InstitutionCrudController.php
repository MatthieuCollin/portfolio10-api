<?php

namespace App\Controller;

use App\Entity\Institution;
use App\Form\InstitutionType;
use App\Form\InstitutionTypeEdit;
use App\Service\ImageService;
use App\Repository\InstitutionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/institution/crud')]
class InstitutionCrudController extends AbstractController
{
    #[Route('/', name: 'app_institution_crud_index', methods: ['GET'])]
    public function index(InstitutionRepository $institutionRepository): Response
    {
        return $this->render('institution_crud/index.html.twig', [
            'institutions' => $institutionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_institution_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,  ImageService $imgService): Response
    {
        $institution = new Institution();
        $form = $this->createForm(InstitutionType::class, $institution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->get('image_url')->getData();
            if ($data) {
                $newName = $imgService->generateUniqueImageName($form->get('name')->getData());
                $imgService->moveImageToDirectory($newName, $data);
                $institution->setImageUrl($newName);
            }


            $entityManager->persist($institution);
            $entityManager->flush();

            return $this->redirectToRoute('app_institution_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('institution_crud/new.html.twig', [
            'institution' => $institution,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_institution_crud_show', methods: ['GET'])]
    public function show(Institution $institution): Response
    {
        return $this->render('institution_crud/show.html.twig', [
            'institution' => $institution,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_institution_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Institution $institution, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InstitutionTypeEdit::class, $institution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_institution_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('institution_crud/edit.html.twig', [
            'institution' => $institution,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_institution_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Institution $institution, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$institution->getId(), $request->request->get('_token'))) {
            $entityManager->remove($institution);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_institution_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
