<?php

namespace App\Controller;


use App\Entity\Duck;
use App\Entity\Quack;
use App\Form\CommentType;
use App\Form\QuackType;
use App\Repository\QuackRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use phpDocumentor\Reflection\DocBlock\TagFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
            'quacks' => $quackRepository->findParentQuacks(),
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

        $quack->setCreatedAt(new \DateTime());
        $quack->setAuteur($this->getUser());
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
     * @param Quack $quack
     * @param QuackRepository $quackRepository
     * @return Response
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
        $this->denyAccessUnlessGranted('edit', $quack);

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
     * @param Request $request
     * @param Quack $quack
     * @return Response
     */
    public function delete(Request $request, Quack $quack): Response
    {
        $this->denyAccessUnlessGranted('delete', $quack);
        if ($this->isCsrfTokenValid('delete' . $quack->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quack);
            $entityManager->flush();
        }
        if ($quack->getParent()) {
            return $this->redirectToRoute('quack_show', array('id' => $quack->getParent()->getId()));
        }
        return $this->redirectToRoute('quack_index');
    }
//
//    /**
//     * @Route("/quack/{quack}/comment/add", name="add_comment", methods={"GET","POST"})
//     * @param Quack $quack
//     * @param Request $request
//     * @param EntityManagerInterface $entityManager
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
//     * @throws Exception
//     */
//
//    public function storeComment(Quack $quack, Request $request, EntityManagerInterface $entityManager)
//    {
//        $comment = new Quack();
//
//        $form = $this->createForm(CommentType::class, $comment);
//        $comment->setCreatedAt(new \DateTime());
//        $comment->setAuteur($this->getUser());
//        $comment->setParent($quack);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $entityManager->persist($comment);
//
//            $entityManager->flush();
//
////            if ($quack->getParent()) {
////                return $this->redirectToRoute('quack_show', array('id' => $quack->getParent()->getId()));
////            }
//            return $this->redirectToRoute('quack_show', array('id' => $quack->getId()));
//        }
//        return $this->render('comments/_comment_form.html.twig', [
//            'form' => $form->createView(),
//            'quack' => $quack
//        ]);
//    }
    /**
     * @Route("/quack/{quack}/comment/add", name="add_comment", methods={"GET","POST"})
     */

    public function storeComment(Quack $quack,  Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager) {
        $comment = new Quack();
        $form = $this->createForm(CommentType::class, $comment);
        $comment->setCreatedAt(new \DateTime());
        $comment->setAuteur($this->getUser());
        $comment->setParent($quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('quack_show', array('id' => $quack->getId()));
        }
        return $this->render('comments/_comment_form.html.twig', [
            'form' => $form->createView(),
            'quack' => $quack
        ]);
    }
}
