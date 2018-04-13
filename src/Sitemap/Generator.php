<?php
namespace App\Sitemap;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Tackk\Cartographer\ChangeFrequency;
use Tackk\Cartographer\Sitemap;

class Generator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var string
     */
    private $hostUrl;

    /**
     * Generator constructor.
     * @param EntityManagerInterface $em
     * @param $hostUrl
     */
    public function __construct(EntityManagerInterface $em, $hostUrl)
    {
        $this->em = $em;
        $this->hostUrl = $hostUrl;
    }

    /**
     *
     */
    public function generate()
    {
        $sitemap = new Sitemap();

        $pages = $this->em->getRepository(Page::class)->findAll();

        foreach ($pages as $page) {

            if(is_null($page->getParent())){
                if ($page->getUrl() == 'cv') {
                    $sitemap->add(
                        $this->hostUrl . '/' . $page->getUrl(),
                        $page->getUpdatedAt()->format('Y-m-d'),
                        ChangeFrequency::MONTHLY,
                        1
                    );
                } else {
                    $sitemap->add(
                        $this->hostUrl . '/' . $page->getUrl(),
                        $page->getUpdatedAt()->format('Y-m-d'),
                        ChangeFrequency::YEARLY,
                        0.9
                    );
                }
            } else {
                $sitemap->add(
                    $this->hostUrl . '/' . $page->getUrl(),
                    $page->getUpdatedAt()->format('Y-m-d'),
                    ChangeFrequency::MONTHLY,
                    0.5
                );
            }
        }

        // Write it to a file
        file_put_contents(__DIR__.'/../../public/sitemap.xml', (string) $sitemap);
    }
}