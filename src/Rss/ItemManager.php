<?php
namespace App\Rss;

use App\Entity\Page;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FeedIo\Feed;
use FeedIo\Feed\Item\Media;
use FeedIo\Feed\ItemInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class ItemManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Feed
     */
    private $feed;

    /**
     * Feed constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->feed = new Feed();
    }
    /**
     * @param Page $page
     * @return $this
     */
    public function addPage(Page $page)
    {
        $item = $this->createItem($page);
        $this->feed->add($item);
        return $this;
    }
    /**
     * @return Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }
    /**
     * @param Page $page
     * @return ItemInterface
     */
    private function createItem(Page $page)
    {
        $item = $this->feed->newItem();

        $content = strip_tags($page->getAbstract());
        $item->setDescription($content)
            ->setTitle($page->getTitle())
            ->setPublicId($page->getId())
            ->setLink('/'.$page->getUrl())
            ->setLastModified($page->getUpdatedAt())
        ;

        return $item;
    }
}