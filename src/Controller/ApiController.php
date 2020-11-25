<?php

namespace App\Controller;

use App\Entity\Quack;
use App\Repository\QuackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/quack", name="api_quack", methods="GET")
     * @param QuackRepository $quackRepository
     * @param SerializerInterface $serializer
     * @return false|string
     */
    //Récupérer la liste des coincoins
    public function index(QuackRepository $quackRepository, SerializerInterface $serializer)
    {
        $quack = $quackRepository->findParentQuacks();

        $json = $serializer->serialize($quack, 'json', ['groups' =>
            'quack:read']);

        return new JsonResponse($json, 200, [], true);


    }
    /**
     * @Route("/api/quack/{id}", name="api_quack_show", methods="GET")
     * @param QuackRepository $quackRepository
     * @param SerializerInterface $serializer
     * @param $id
     * @return Response
     */
    //Récuperer un coincoin avec son image
    public function show(QuackRepository $quackRepository, SerializerInterface $serializer,$id): Response
    {
        $quack = $quackRepository->find($id);
        $json = $serializer->serialize($quack, 'json', ['groups' =>
            'quack:read']);
        return new JsonResponse($json, 200, [], true);
    }
    /**
     * @Route("/api/quack/tag/{tag}", name="api_quack_tag", methods="GET")
     * @param QuackRepository $quackRepository
     * @param SerializerInterface $serializer
     * @param $tag
     * @return Response
     */
    //Rechercher des coincoins par tag
    public function byTag(QuackRepository $quackRepository, SerializerInterface $serializer,$tag): Response
    {
        $quack = $quackRepository->find($tag);
        dd($quack);
        $json = $serializer->serialize($quack, 'json', ['groups' =>
            'quack:read']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/api/quack", name="api_quack_add", methods="POST")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function add(EntityManagerInterface $entityManager, Request $request,
                        SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $contenu = $request->getContent();
        try {
            $quack = $serializer->deserialize($contenu, Quack::class, 'json');
            $errors = $validator->validate($quack);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            $entityManager->persist($quack);
            $entityManager->flush();
            return $this->json($quack, 201, [], [
                'groups' => 'quack:read'
            ]);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

}
