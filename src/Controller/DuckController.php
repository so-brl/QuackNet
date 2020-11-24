<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\DuckType;
use App\Repository\DuckRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/duck")
 */
class DuckController extends AbstractController
{
    /**
     * @Route("/", name="duck_index", methods={"GET"})
     * @param DuckRepository $duckRepository
     * @return Response
     */
    public function index(DuckRepository $duckRepository): Response
    {
        return $this->render('duck/index.html.twig', [
            'ducks' => $duckRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="duck_new", methods={"GET","POST"})
     */
    public function new(Request $request,  UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $duck = new Duck();
        $form = $this->createForm(DuckType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $duck->setPassword(
                $passwordEncoder->encodePassword(
                    $duck,
                    $form->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($duck);
            $entityManager->flush();

            return $this->redirectToRoute('duck_index');
        }

        return $this->render('duck/new.html.twig', [
            'duck' => $duck,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="duck_show", methods={"GET"})
     */
    public function show(Duck $duck): Response
    {
        return $this->render('duck/show.html.twig', [
            'duck' => $duck,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="duck_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Duck $duck): Response
    {
        $this->denyAccessUnlessGranted('edit', $duck);
        $form = $this->createForm(DuckType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('duck_index');
        }

        return $this->render('duck/edit.html.twig', [
            'duck' => $duck,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="duck_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Duck $duck): Response
    {
        $this->denyAccessUnlessGranted('delete', $duck);
        $currentUserId = $this->getUser();
        if ($currentUserId == $duck)
        {
            $session = $this->get('session');
            $session = new Session();
            $session->invalidate();
        }
        if ($this->isCsrfTokenValid('delete'.$duck->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($duck);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quack_index');
    }
//    /**
//     * @Route("/register", name="duck_register", methods={"GET","POST"})
//     */
//    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
//    {
//        $duck = new Duck();
//        $form = $this->createForm(DuckType::class, $duck);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $duck->setPassword(
//                $passwordEncoder->encodePassword(
//                    $duck,
//                    $form->get('password')->getData()
//                )
//            );
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($duck);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('duck_index');
//        }
//
//        return $this->render('duck/register.html.twig', [
//            'duck' => $duck,
//            'form' => $form->createView(),
//        ]);
//    }
}
