<?php

namespace App\Controller;


use App\Entity\Quack;
use App\Form\QuackType;
use App\Repository\QuackRepository;
use App\Service\FileUploader;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/quack")
 */
class QuackController extends AbstractController
{
    /**
     * @Route("/", name="quack_index", methods={"GET"})
     * @param QuackRepository $quackRepository
     * @return Response
     */
    public function index(QuackRepository $quackRepository): Response
    {

        return $this->render('quack/index.html.twig', [
            'quacks' => $quackRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="quack_new", methods={"GET","POST"})
     * @param Request $request
     * @param $fileUploader
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $quack = new Quack();

        $form = $this->createForm(QuackType::class, $quack);

        $quack ->setCreatedAt(new \DateTime());
        $quack ->setAuteur($this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quackPicture = $form->get('uploadFileName')->getData();
            if ($quackPicture) {
                $uploadFileName = $fileUploader->uploadDuckImage($quackPicture);
                $quack->setUploadFileName($uploadFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quack);
            $entityManager->flush();

            return $this->redirectToRoute('quack_index');
        }

        return $this->render('quack/new.html.twig', [
            'quack' => $quack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quack_show", methods={"GET"})
     */
    public function show(Quack $quack): Response
    {



        return $this->render('quack/show.html.twig', [
            'quack' => $quack,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="quack_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Quack $quack): Response
    {

        $author = $quack->getAuteur();
        // ==> retrives user object

        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quack_index');
        }

        return $this->render('quack/edit.html.twig', [
            'quack' => $quack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quack_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Quack $quack): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quack->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quack);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quack_index');
    }
}
