<?php
namespace App\Algolia;

use App\Entity\Page;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EventListener implements EventSubscriber
{
    /**
     * @var API
     */
    private $api;
    /**
     * @var string
     */
    private $env;

    /**
     * EventListener constructor.
     * @param API $api
     * @param $env
     */
    public function __construct(API $api, $env)
    {
        $this->api = $api;
        $this->env = $env;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
        );
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \AlgoliaSearch\AlgoliaException
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \AlgoliaSearch\AlgoliaException
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \AlgoliaSearch\AlgoliaException
     */
    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Page) {
            /**
             * @var Page $entity
             */
            if($this->env !== 'test') {
                if ($entity->getTemplate()->getFile() == 'article.html.twig') {
                    $this->api->push($entity);
                }
            }
        }

    }
}