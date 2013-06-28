<?php namespace Wordpress\Api;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

class WordpressClient extends Client {

    /**
     * Create an object with required configs.
     * 
     * @param  array  $config Associative array of config values to pass into command object.
     * @return object         Object of this class.
     */
    public static function factory($config = array())
    {
        $default = array(
            'base_url'     => 'http://api.wordpress.org',
            'curl.options' => array(
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_TIMEOUT => 120,
                'body_as_string' => true,
            ),
        );

        $required = array('base_url');
        $config = Collection::fromConfig($config, $default, $required);
        $description = ServiceDescription::factory(__DIR__.'/service.json');

        $client = new self($config->get('base_url'), $config);
        $client->setDescription($description);

        return $client;
    }

}