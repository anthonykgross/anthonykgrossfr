<?php
namespace App\Algolia;

use AlgoliaSearch\Client;
use Symfony\Component\Serializer\SerializerInterface;

class API
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * API constructor.
     * @param $applicationID
     * @param $adminKey
     * @param SerializerInterface $serializer
     */
    public function __construct($applicationID, $adminKey, SerializerInterface $serializer)
    {
        $this->client = new Client($applicationID, $adminKey);
        $this->serializer = $serializer;
    }

    /**
     * @param $entity
     * @throws \AlgoliaSearch\AlgoliaException
     */
    public function push($entity)
    {
        $index = $this->client->initIndex('pages');

        $json = $this->serializer->serialize(
            $entity,
            'json', array('groups' => array('page'))
        );

        $obj = json_decode($json, true);
        $obj['objectID'] = $entity->getId();
        unset($obj['content']);

        $index->saveObject($obj);
    }

    /**
     * @param $string
     * @return mixed
     * @throws \AlgoliaSearch\AlgoliaException
     */
    public function search($string)
    {
        $index = $this->client->initIndex('pages');
        return $index->search($string);
    }
}