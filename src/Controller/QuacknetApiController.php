<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Repository\DuckRepository;
use App\Repository\QuackRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class QuacknetApiController extends AbstractController
{
    //Récupérer la liste des coincoins
    /**
     * @Route("/api/quack/", name="quacknet_api")
     * @param QuackRepository $quackRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(QuackRepository $quackRepository, SerializerInterface $serializer): Response
    {
        $quack = $quackRepository->findParentQuacks();
        $json = $serializer->serialize($quack, 'json', ["groups" =>
            "read"]);
        return new JsonResponse($json, 200, [], true);
    }

    //Récuperer un coincoin par tag

    /**
     * @Route("/api/quack/search/", name="api_quack_by_tag", methods={"GET"})
     * @param Request $request
     * @param QuackRepository $quackRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function showQuacksWithTagsFilter(Request $request, QuackRepository $quackRepository, SerializerInterface $serializer): Response
    {
        $query = $request->get('q');
        $quacks = $quackRepository->findByTag($query);
        $json = $serializer->serialize($quacks, 'json', ['groups' => 'read']);
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    //Récuperer un coincoin avec son image

    /**
     * @Route("/api/quack/{id}", name="api_quack_show", methods="GET")
     * @param QuackRepository $quackRepository
     * @param TagRepository $tagRepository
     * @param SerializerInterface $serializer
     * @param $id
     * @return Response
     */
    public function showQuack(QuackRepository $quackRepository, TagRepository $tagRepository, SerializerInterface $serializer, $id): Response
    {
        $quack = $quackRepository->find($id);
        $json = $serializer->serialize($quack, 'json', ['groups' =>
            'read']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/api/quack/{id}", name="api_quack_delete", methods={"DELETE"})
     * @param QuackRepository $quackRepository
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function deleteQuack(QuackRepository $quackRepository, $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $quack = $quackRepository->find($id);
        $entityManager->remove($quack);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Quack deleted'], Response::HTTP_NO_CONTENT);
    }


//    /**
//     * @Route("/api/duck/{id}", name="update", methods={"PUT"})
//     * @param Duck $duck
//     * @param UserPasswordEncoderInterface $passwordEncoder
//     * @param Request $request
//     * @param EntityManagerInterface $entityManager
//     * @param SerializerInterface $serializer
//     * @return Response
//     */
//    public function updateDuck(Duck $duck,UserPasswordEncoderInterface $passwordEncoder, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
//    {
//        $query = $request->query;
//        $lastname = $query->get('lastname');
//        $firstname = $query->get('firstname');
//        $duckname = $query->get('duckname');
//        $email= $query->get('email');
//        $password = $query->get('password');
//        if($lastname){$duck->setLastname($lastname);}
//        if($firstname){$duck->setFirstname($firstname);}
//        if($duckname){$duck->setDuckname($duckname);}
//        if($email){$duck->setEmail($email);}
//        if($password){$duck->setPassword($passwordEncoder->encodePassword($duck, $password));}
//        $duck->setRoles(['ROLE_USER']);
//        $entityManager->persist($duck);
//        $entityManager->flush();
//        return $this->json($duck, 200, [], ['groups' => 'read']);
//    }

    /**
     * @Route("/api/duck/{id}", name="api_duck_update", methods={"PUT"})
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param DuckRepository $duckRepository
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function updateDuck(UserPasswordEncoderInterface $passwordEncoder, DuckRepository $duckRepository, $id, EntityManagerInterface $entityManager, Request $request): Response
    {

        $duck = $duckRepository->find($id);
        $data = $request->getContent();
        parse_str($data, $data_arr);
        if ($data_arr['password']) {
            $encoded = $passwordEncoder->encodePassword($duck, $data_arr['password']);
        }
        $json = json_encode($data_arr, true);

        if ($duck) {
            $this->get('serializer')->deserialize($json, Duck::class, 'json', ['object_to_populate' => $duck]);
            $duck->setPassword($encoded);
        }

        $entityManager->persist($duck);
        $entityManager->flush();

        return new Response('', Response::HTTP_OK);
    }

    /**
     * @Route("/api/duck/", name="create", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function createDuck(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $receivedJson = $request->getContent();
        $pwd = json_decode($receivedJson)->password;
        $duck = new Duck();
        $pwd = $passwordEncoder->encodePassword($duck, $pwd);
        $duck = $serializer->deserialize($receivedJson, Duck::class, 'json');
        $duck->setPassword($pwd);
        $entityManager->persist($duck);
        $entityManager->flush();
        return $this->json($duck, 200, [], ['groups' => 'read']);
    }


    /**
     * @Route("/api/duck", name="api_duck_new", methods={"POST"})
     * @param SerializerInterface $serializer
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function newDuck(SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, Request $request): Response
    {
        $data = $request->getContent();
        parse_str($data, $data_arr);
        $password = $data_arr['password'];
        $json = json_encode($data_arr, true);
        $duck = $this->get('serializer')->deserialize($json, Duck::class, 'json');
        $duck->setPassword($passwordEncoder->encodePassword(
            $duck,
            $password));
        $duck->setRoles(['ROLE_USER']);
        $entityManager->persist($duck);
        $entityManager->flush();

        return new Response('', Response::HTTP_CREATED);

    }
}
