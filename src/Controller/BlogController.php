<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Form\CategoryType;
use App\Form\ArticleType;
use App\Form\TagType;
use Symfony\Component\HttpFoundation\Request;
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

        $tags = $this
        ->getDoctrine()
        ->getRepository(Tag::class)
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
        return $this->render('blog/list.html.twig', ['articles' => $articles, 'categories' => $categories, 'tags' => $tags]);
    }

   // /**
   //  * @Route("/add", methods={"GET"})
   //  */
   /*  public function add()
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
    } */

    /**
     * @Route("/category/{name}", methods={"GET"}, name="show_category")
     */
    public function showByCategory(string $name) :Response
    { 
        $category = $this
        ->getDoctrine()
        ->getRepository(Category::class)
        ->findOneByName($name);

        $articles = $this
        ->getDoctrine()
        ->getRepository(Article::class)
        ->findByCategory($category, ['id' => 'DESC'], 3);
        // ou ->findBy(['category'=>$category], ['id' => 'DESC'], 3);

        return $this->render('blog/category_details.html.twig', [
            'category'=>$category,
            'articles' => $articles
            ]);
    }

   /**
 * @Route("/article/{id}", name="show_article")
 */
    public function showByArticle(Article $article) :Response
    {
        $category = $this
        ->getDoctrine()
        ->getRepository(Category::class)
        ->findOneById($article->getCategory());
        //ou ->find(['id' => $article->getCategory()->getId()])

        $tags = $this
        ->getDoctrine()
        ->getRepository(Tag::class)
        ->findAll();

        return $this->render('blog/article_details.html.twig', [
            'article'=>$article,
            'tags' => $tags,
            'category' => $category
        ]);

    }

      /**
    * @Route("/tag/{id}", name="show_tag")
    */
    public function showByTag(Tag $tag) :Response
    {
        $articles = $tag->getArticles();

        return $this->render('blog/tag_details.html.twig', [
            'tag'=>$tag,
            'articles' => $articles
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

    /** 
     * Add new form
     *
     * 
     *
     * @Route("/category/add/", name="add_category")
     */
    public function addCategory(Request $request) :Response
    {
        $category = new Category();
        $form = $this->createForm(
            CategoryType::class,
            $category,
             ['method' => Request::METHOD_POST]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }

        return $this->render(
            'blog/category_add.html.twig',
            [
                'category' => $category,
                'form' => $form->createView(),
            ]
        );
    }

    /** 
     * Add new article
     *
     * 
     *
     * @Route("/article/add/", name="add_article")
     */
    public function addArticle(Request $request) :Response
    {
        $article = new Article();
        $form = $this->createForm(
            ArticleType::class,
            $article,
             ['method' => Request::METHOD_POST]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
        }

        return $this->render(
            'blog/article_add.html.twig',
            [
                'article' => $article,
                'form' => $form->createView(),
            ]
        );
    }

    /** 
     * Add new tag
     *
     * 
     *
     * @Route("/tag/add/", name="add_tag")
     */
    public function addTag(Request $request) :Response
    {
        $tag = new Tag();
        $form = $this->createForm(
            TagType::class,
            $tag,
             ['method' => Request::METHOD_POST]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();
        }

        return $this->render(
            'blog/tag_add.html.twig',
            [
                'tag' => $tag,
                'form' => $form->createView()
            ]
        );
    }
}
