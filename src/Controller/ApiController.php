<?php

namespace App\Controller;

use App\Entity\Quack;
use App\Entity\Tag;
use App\Repository\QuackRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ApiController extends AbstractController
{
    //Récupérer la liste des coincoins
    /**
     * @Route("/api/quack", name="api_quack", methods="GET")
     * @param QuackRepository $quackRepository
     * @param SerializerInterface $serializer
     * @return false|string
     */
    public function index(QuackRepository $quackRepository, SerializerInterface $serializer)
    {
        $quack = $quackRepository->findParentQuacks();

        $json = $serializer->serialize($quack, 'json', ['groups' =>
            'quack:read']);

        return new JsonResponse($json, 200, [], true);


    }
    //Récuperer un coincoin avec son image
    /**
     * @Route("/api/quack/{id}", name="api_quack_show", methods="GET")
     * @param QuackRepository $quackRepository
     * @param SerializerInterface $serializer
     * @param $id
     * @return Response
     */
    public function showQuack(QuackRepository $quackRepository, TagRepository $tagRepository,SerializerInterface $serializer,$id): Response
    {
        if(gettype($id)!='string'){
            $quack = $quackRepository->find($id);
            $json = $serializer->serialize($quack, 'json', ['groups' =>
                'quack:read']);
            return new JsonResponse($json, 200, [], true);
        }
        return $this->search($serializer ,$tagRepository, $quackRepository ,$id);
    }


    //Rechercher des coincoins par tag
    /**
     * @Route("/api/quack/search/{tag}", name="api_quack_tag", methods="GET")
     * @param SerializerInterface $serializer
     * @param TagRepository $tagRepository
     * @param $tag
     * @return JsonResponse
     */
    public function search(SerializerInterface $serializer,TagRepository $tagRepository, QuackRepository $quackRepository, $tag)
    {
        $quack = $quackRepository->findByTag($tag);

        $json = $serializer->serialize($quack, 'json', ['groups' =>
            'quack:read']);
        return new JsonResponse($json, 200, [], true);
    }

}
