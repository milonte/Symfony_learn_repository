<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/list", methods={"GET"}, name="list")
     */
    public function list()
    {
        $articles = $this
            ->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        $categories = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }
        if (!$categories) {
            throw $this->createNotFoundException(
                'No category found in category\'s table.'
            );
        }
        return $this->render('blog/list.html.twig', ['articles' => $articles, 'categories' => $categories]);
    }

    /**
     * @Route("/add", methods={"GET"})
     */
    public function add()
    {
        $entityManager = $this->getDoctrine()->getManager();

        for ($i = 0; $i < 4; $i++) {
            $category = new Category;
            $category->setName("category number " . $i);

            $entityManager->persist($category);
            $entityManager->flush();

            for ($j = 0; $j < 3; $j++) {
                $article = new Article;
                $article->setTitle("article number " . $j);
                $article->setContent("content " . $j);
                $category->addArticle($article);
                $entityManager->persist($article);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('list', [
            'articles' => 
            $this
            ->getDoctrine()
            ->getRepository(Article::class)
            ->findAll()
        ]);
    }

    /**
     * @Route("/category/{name}", methods={"GET"}, name="show_category")
     */
    public function showByCategory(string $name) :Response
    { 
        $category = $this
        ->getDoctrine()
        ->getRepository(Category::class)
        ->findOneByName($name);

        return $this->render('blog/category_details.html.twig', [
            'category'=>$category,
            'articles' => $this
                ->getDoctrine()
                ->getRepository(Article::class)
                ->findBy(['category'=>$category->getId()], ['id' => 'DESC'], 3)
        ]);
    }

   /**
 * @Route("/article/{id}", name="show_article")
 */
    public function showByArticle(Article $article) :Response
    {
        return $this->render('blog/article_details.html.twig', [
            'article'=>$article,
            'category' => $this
                ->getDoctrine()
                ->getRepository(Category::class)
                ->findOneById($article->getCategory()->getId())
                //ou ->find(['id' => $article->getCategory()->getId()])
        ]);

    }

    //ancienne solut°

    // /**
    // * @Route("/showArticle/{id}", methods={"GET"}, name="articleDetails")
    // */
    /* public function articleDetails(int $id)
    { 
        $article = $this->getDoctrine()  
            ->getRepository(Article::class)  
            ->findOneBy(['id' => $id]);  

        $category = $article->getCategory();

        return $this->render('blog/article_details.html.twig', ['article' => $article, 'category' => $category]);   
    } */

    // /**
    // * @Route("/showCategory/{id}", methods={"GET"}, name="categoryDetails")
    // */
    /* public function categoryDetails(int $id)
    { 
        $category = $this->getDoctrine()  
            ->getRepository(Category::class)  
            ->findOneBy(['id' => $id]);  

        $articles = $category->getArticles();

        return $this->render('blog/category_details.html.twig', ['articles' => $articles, 'category' => $category]);
        
    }
 */

}
