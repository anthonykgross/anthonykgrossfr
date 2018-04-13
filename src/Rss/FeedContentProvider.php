<?php
namespace App\Rss;

use App\Entity\Page;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use Debril\RssAtomBundle\Provider\FeedContentProviderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FeedIo\FeedInterface;
class FeedContentProvider implements FeedContentProviderInterface
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
     * @param array $options
     *
     * @throws FeedNotFoundException
     *
     * @return FeedInterface
     */
    public function getFeedContent(array $options)
    {
        $pages = $this->em->getRepository(Page::class)->getArticles($options['id']);

        // fetch feed from data repository
        foreach ($pages as $page) {
            $this->itemManager->addPage($page);
        }
        $feed = $this->itemManager->getFeed();

        // if the feed is an actual FeedInterface instance, then return it
        if ($feed instanceof FeedInterface) {
            return $feed;
        }
        // $feed is null, which means no Feed was found with this id.
        throw new FeedNotFoundException();
    }
}