<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // creation de 3 category

        for ($i=1; $i <= 3 ; $i++) { 
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());
            
            $manager->persist($category);
        

        for($j =0; $j<= mt_rand(4, 8); $j++){
            $article= new Article();
            $content= '<p>'.join($faker->paragraphs(5), '</p><p>').'</p>';

            $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreateAt($faker->dateTimeBetween('-6 mounths'))
                    ->setCategory($category);

            $manager->persist($article);
            for ($k=1; $k < mt_rand(3, 10); $k++) { 
                $comment= new Comment();
                $content= '<p>'.join($faker->paragraphs(2), '</p><p>').'</p>';
                $days = (new \DateTime())->diff($article->getCreateAt())->days;

                $comment->setAuthor($faker->name)
                        ->setContent($content)
                        ->setCreateAt($faker->dateTimeBetween('-'.$days.' days'))
                        ->setArticle($article);

                $manager->persist($comment);

            }
        }
    }


        $manager->flush();
    }
}
