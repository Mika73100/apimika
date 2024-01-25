<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiRegisterController extends AbstractController

{

    #[Route('/api/register', name: 'app_register', methods: ["POST"])]
    public function index(Request $request, UserRepository $user, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasherInterface): Response
    {
        // TODO - use Symfony forms & validation
        $data = json_decode($request->getContent(),true);

        //dd('coucou');
        $email=$data['email'];
        $password=$data['password'];
        
        // // ici je verifie si l'email existe:
        $email_exist=$user->findOneByEmail($email);

        if ($email_exist) 
        {
            return new JsonResponse
            (
                [
                    'status'=>false,
                    'message'=>'Cet email n est pas valable !'
                ]
            );
        }

        else
        {
           $user= new User();

           $user->setEmail($email)
                ->setPassword($passwordHasherInterface->hashPassword($user,$password))
                ->setRoles(['ROLE_USER']);
                
                $em->persist($user);
                $em->flush();
        }

            return new JsonResponse
            (
                [
                    'status'=>true,
                    'message'=>'User enregistrer !'
                ]
            );
    }
}
