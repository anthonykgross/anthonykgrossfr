<?php

namespace App\DataFixtures;

use App\Entity\Template;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TemplateFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $templateBlank = new Template();
        $templateBlank->setFile('blank.html.twig');
        $manager->persist($templateBlank);

        $templateCategory = new Template();
        $templateCategory->setFile('category.html.twig');
        $manager->persist($templateCategory);

        $templateArticle = new Template();
        $templateArticle->setFile('article.html.twig');
        $manager->persist($templateArticle);

        $manager->flush();
    }
}
