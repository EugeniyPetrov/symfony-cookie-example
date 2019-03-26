<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class Example extends AbstractController
{
    /**
     * @Route("/first", name="first")
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function first(SerializerInterface $serializer)
    {
        $response = $this->render("first.html.twig", []);

        $user = new User("John", "Smith", "john.smith@example.com");
        $serializedUser = $serializer->serialize($user, "json");
        $response->headers->setCookie(new Cookie("my_user", $serializedUser));

        return $response;
    }

    /**
     * @Route("/second", name="second")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function second(Request $request, SerializerInterface $serializer)
    {
        $serializedUser = $request->cookies->get("my_user");
        $user = $serializer->deserialize($serializedUser, User::class, "json");

        return $this->render("second.html.twig", [
            "user" => $user,
        ]);
    }
}