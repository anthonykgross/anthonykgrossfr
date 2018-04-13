<?php
namespace App\Cache;


use GuzzleHttp\Client;

class CloudFlare
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $zone;

    /**
     * @var string
     */
    const ENDPOINT_URL = 'https://api.cloudflare.com/client/v4/';

    /**
     * CloudFlare constructor.
     * @param $email
     * @param $key
     * @param $zone
     */
    public function __construct($email, $key, $zone)
    {
        $this->email = $email;
        $this->key = $key;
        $this->zone = $zone;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function purgeCache()
    {
        $client = new Client([
            'base_uri' => self::ENDPOINT_URL
        ]);

        return $client->delete(
            'zones/'.$this->zone.'/purge_cache',
            [
                'headers' => [
                    'X-Auth-Email' => $this->email,
                    'X-Auth-Key' => $this->key,
                    'Content-Type' => 'application/json'
                ],
                'body' => '{"purge_everything":true}'
            ]
        );
    }

}