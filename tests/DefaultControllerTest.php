<?php

namespace App\Tests;

use App\Entity\Page;
use App\Entity\Template;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Client
     */
    private $client;

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine')->getManager();

        $pages = $this->em->getRepository(Page::class)->findAll();
        foreach ($pages as $p) {
            $this->em->remove($p);
        }

        $templates = $this->em->getRepository(Template::class)->findAll();
        foreach ($templates as $t) {
            $this->em->remove($t);
        }
        $this->em->flush();
    }

    /**
     * @return Page
     */
    private function getFixturePage()
    {
        $template = new Template();
        $template->setFile('article.html.twig');

        $page = new Page();
        $page->setUrl('test')
            ->setIsOnline(true)
            ->setAlias('test')
            ->setTemplate($template)
            ->setTitle('MyTitle');
        return $page;
    }

    /**
     *
     */
    public function testNotFound()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h2:contains("Article introuvable")')->count());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testOnline()
    {
        $page = $this->getFixturePage();
        $this->em->persist($page->getTemplate());
        $this->em->persist($page);
        $this->em->flush();

        $clientA = static::createClient();
        $crawler = $clientA->request('GET', '/');
        $this->assertSame(404, $clientA->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h2:contains("Article introuvable")')->count());

        // check metadata
        // title
        $title = 'AnthonyKGross.fr - La page introuvable';
        $this->assertSame(
            $title,
            $crawler->filter('title')->text()
        );
        $this->assertSame(
            $title,
            $crawler->filter('meta[property="og:title"]')->attr('content')
        );
        $this->assertSame(
            $title,
            $crawler->filter('meta[name="twitter:title"]')->attr('content')
        );

        // keywords
        $this->assertSame(
            'freelance, marseille, developpeur, formation, ecommerce',
            $crawler->filter('meta[name=keywords]')->attr('content')
        );

        // description
        $description = 'Consultez la page introuvable.';
        $this->assertSame(
            $description,
            $crawler->filter('meta[name=description]')->attr('content')
        );
        $this->assertSame(
            $description,
            $crawler->filter('meta[property="og:description"]')->attr('content')
        );
        $this->assertSame(
            $description,
            $crawler->filter('meta[name="twitter:description"]')->attr('content')
        );

        $clientB = static::createClient();
        $crawler = $clientB->request('GET', '/test');
        $this->assertSame(200, $clientB->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h2:contains("MyTitle")')->count());

        // check metadata
        // title
        $title = 'Developpeur freelance sur Marseille';
        $this->assertSame(
            $title,
            $crawler->filter('title')->text()
        );
        $this->assertSame(
            $title,
            $crawler->filter('meta[property="og:title"]')->attr('content')
        );
        $this->assertSame(
            $title,
            $crawler->filter('meta[name="twitter:title"]')->attr('content')
        );

        // keywords
        $this->assertSame(
            'freelance, marseille, developpeur, formation, ecommerce',
            $crawler->filter('meta[name=keywords]')->attr('content')
        );

        // description
        $description = 'Développeur freelance sur Marseille vous propose son expertise, développement, ecommerce & formation.';
        $this->assertSame(
            $description,
            $crawler->filter('meta[name=description]')->attr('content')
        );
        $this->assertSame(
            $description,
            $crawler->filter('meta[property="og:description"]')->attr('content')
        );
        $this->assertSame(
            $description,
            $crawler->filter('meta[name="twitter:description"]')->attr('content')
        );
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testNotOnline()
    {
        $page = $this->getFixturePage();
        $page->setIsOnline(false);

        $this->em->persist($page->getTemplate());
        $this->em->persist($page);
        $this->em->flush();

        $crawler = $this->client->request('GET', '/test');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h2:contains("Article introuvable")')->count());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testMetadata()
    {
        $page = $this->getFixturePage();
        $page->setMetaTitle('my meta title')
                ->setMetaDescription('my meta description')
                ->setMetaKeywords('keywords, test, developer');
        $this->em->persist($page->getTemplate());
        $this->em->persist($page);
        $this->em->flush();

        $clientA = static::createClient();
        $crawler = $clientA->request('GET', '/test');
        $this->assertSame(200, $clientA->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h2:contains("MyTitle")')->count());

        // check metadata
        // title
        $title = 'my meta title';
        $this->assertSame(
            $title,
            $crawler->filter('title')->text()
        );
        $this->assertSame(
            $title,
            $crawler->filter('meta[property="og:title"]')->attr('content')
        );
        $this->assertSame(
            $title,
            $crawler->filter('meta[name="twitter:title"]')->attr('content')
        );

        // keywords
        $this->assertSame(
            'keywords, test, developer',
            $crawler->filter('meta[name=keywords]')->attr('content')
        );

        // description
        $description = 'my meta description';
        $this->assertSame(
            $description,
            $crawler->filter('meta[name=description]')->attr('content')
        );
        $this->assertSame(
            $description,
            $crawler->filter('meta[property="og:description"]')->attr('content')
        );
        $this->assertSame(
            $description,
            $crawler->filter('meta[name="twitter:description"]')->attr('content')
        );
    }
}
