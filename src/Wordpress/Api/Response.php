<?php namespace Wordpress\Api;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;

class Response implements ResponseClassInterface {

    protected $data;

    /**
     * Create an object to handle response from api.
     * 
     * @param  OperationCommand $command The guzzle command for the api.
     * @return object                    Instance of this class.
     */
    public static function fromCommand(OperationCommand $command)
    {
        $data = unserialize(
            (string) $command
                ->getResponse()
                ->getBody()
        );

        return new self($data);
    }

    public function __construct($data)
    {
        $this->data = (array) $data;
    }

    public function getData()
    {
        return $this->data;
    }

}