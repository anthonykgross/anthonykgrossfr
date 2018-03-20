<?php
namespace App\Algolia;

use App\Entity\Page;
use App\Sitemap\Generator;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EventListener
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
     * @var Generator
     */
    private $generator;

    /**
     * EventListener constructor.
     * @param API $api
     * @param Generator $generator
     * @param $env
     */
    public function __construct(API $api, Generator $generator, $env)
    {
        $this->api = $api;
        $this->env = $env;
        $this->generator = $generator;
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
                    $this->generator->generate();
                }
            }
        }

    }
}