<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Security;

class AppFixtures extends Fixture
{

    private static $articleImages = [
        '11.jpeg',
        '22.jpeg',
        '33.jpeg',
        '111.jpeg',
        '222.jpeg',
        '333.jpeg',
    ];

    private static $articleUsers = [
        'ilqar@mail.ru',
        'yusif@mail.ru',
        'ilqar34@mail.ru',
        'tural@mail.ru',
        'ehmed@mail.ru',
    ];

    public function load(ObjectManager $manager )
    {
        $faker = Factory::create();


        foreach (range(0,6) as $cat){

            $category = new Category();
            $category->setName($faker->realText(15));
            $category->setDescription($faker->text(30));
            $manager->persist($category);

            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword('12345');
            $manager->persist($user);



            foreach ( range(0,10) as $ppp){

               $post = new Post();

               $post->setTitle($faker->realText(150));
               $post->setContent($faker->text(700));
               $post->setImage($faker->randomElement(self::$articleImages));
               $post->setAuthor($user);
               $post->setCategory($category);

               $manager->persist($post);


               foreach (range(0,7) as $cc) {
                   $comment = new Comment();
                   $comment->setContent($faker->text(160));
                   $comment->setPost($post);
                   $manager->persist($comment);
               }


               foreach (range(0,4) as $tt){
                   $tag = new Tag();
                   $tag->setName($faker->realText(10));
                   $manager->persist($tag);
                   $tag->addPost($post);
               }

           }


        }


        $manager->flush();
    }
}
