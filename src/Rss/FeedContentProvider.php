<?php
namespace App\Rss;

use App\Entity\Page;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use Debril\RssAtomBundle\Provider\FeedProviderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FeedIo\FeedInterface;
use Symfony\Component\HttpFoundation\Request;

class FeedContentProvider implements FeedProviderInterface
{
    /**
     * @var ItemManager
     */
    protected $itemManager;
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * FeedContentProvider constructor.
     * @param EntityManagerInterface $em
     * @param ItemManager $itemManager
     */
    public function __construct(EntityManagerInterface $em, ItemManager $itemManager)
    {
        $this->itemManager = $itemManager;
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @return FeedInterface
     * @throws FeedNotFoundException
     */
    public function getFeed(Request $request): FeedInterface
    {
        $pages = $this->em->getRepository(Page::class)->getArticles($request->query->get('id', null));

        // fetch feed from data repository
        foreach ($pages as $page) {
            $this->itemManager->addPage($page);
        }

        return $this->itemManager->getFeed();
    }
}