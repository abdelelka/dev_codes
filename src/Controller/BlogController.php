<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Entity\Category;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        $articles =$repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController' ,
            'articles'=> $articles
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('blog/home.html.twig');

        
     }
     /**
      * @Route("/blog/{id}", name="blog_show")
      */
     public function show(Article $article){
         //$repo = $this->getDoctrine()->getRepository(Article::class);
         //$article = $repo->find($id);
        return $this->render('blog/show.html.twig',[
            'article'=> $article
        ]);
    }
    /**
     * @Route("/new", name="blog_create")
     * @Route("/new/{id}/edit", name="blog_edit")
     */
    public function create(Article $article = null,Request $request, ObjectManager $manager){
        if (!$article) {
            $article = new Article();
        }

        $form = $this->createFormBuilder($article)
                     ->add('title')
                     ->add('category', EntityType::class, [
                         'class' => Category::class,
                         'choice_label' => 'title'
                     ])
                     ->add('content')
                     ->add('image')
                     ->getForm();   
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreateAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();
            
            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);

        }

        /**dump($request);
        if ($request->request->count() > 0) {
            $article= new Article();
            $article->setTitle($request->request->get('title'))
                    ->setContent($request->request->get('content'))
                    ->setImage($request->request->get('image'))
                    ->setCreateAt(new \DateTime());

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }
        */
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView() ,
            'editMode' => $article->getId() !== null 
         ]);
    }
}
