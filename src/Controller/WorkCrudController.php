<?php

namespace App\Controller;

use App\Entity\Work;
use App\Form\Work1Type;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/work/crud')]
class WorkCrudController extends AbstractController
{
    #[Route('/', name: 'app_work_crud_index', methods: ['GET'])]
    public function index(WorkRepository $workRepository): Response
    {
        return $this->render('work_crud/index.html.twig', [
            'works' => $workRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_work_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $work = new Work();
        $form = $this->createForm(Work1Type::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->get('image_url')->getData();

            
            if ($data) {
                
                $newName = $form->get('name')->getData();
                
                // Move the file to the directory where brochures are stored
                try {
                   
                    $filesystem = new Filesystem();
                    $filesystem->copy(
                        $data->getPathname(),
                        "/var/www/portfolio30/static/media/" . str_replace(' ', '_', strtolower($newName)) . ".png"
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }

            $entityManager->persist($work);
            $entityManager->flush();


            return $this->redirectToRoute('app_work_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('work_crud/new.html.twig', [
            'work' => $work,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_work_crud_show', methods: ['GET'])]
    public function show(Work $work): Response
    {
        return $this->render('work_crud/show.html.twig', [
            'work' => $work,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_work_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Work $work, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Work1Type::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_work_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('work_crud/edit.html.twig', [
            'work' => $work,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_work_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Work $work, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$work->getId(), $request->request->get('_token'))) {
            $entityManager->remove($work);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_work_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
