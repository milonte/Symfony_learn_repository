<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use App\Service\Slugify;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {
            $faker = Faker\Factory::create('en_US');

            $article = new Article();
            
            $article->setTitle(mb_strtolower($faker->sentence));
            $article->setContent(mb_strtolower($faker->text));
            
            $slugify = new Slugify();
            $slug = $slugify->generate($article->getTitle());
            $article->setSlug($slug);
            
            $manager->persist($article);
            $article->setCategory($this->getReference('category_index_'.rand(0,4)));
        }
        $manager->flush();
    }
}
