<?php
    // src\DataFixtures\AppFixtures.php
 
namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un user "normal"
        $user = new User();
        $user->setEmail("user@articleapi.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);
        
        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("Michael-73@live.fr");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);

        // Création des auteurs.
        $listArticle = [];
        for ($i = 0; $i < 10; $i++) {
            // Création de l'auteur lui-même.
            $article = new Article();
            $article->setTitle("Le monde part en brouette " . $i);
            $article->setDescription("ici je parle de brouette " . $i);
            $article->setDate('2022-01-15');
           

            $manager->persist($article);

            // On sauvegarde l'auteur créé dans un tableau.
            $listArticle[] = $article;
        }
        print_r('ok bdd');
        $manager->flush();
   }
}