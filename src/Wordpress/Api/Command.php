<?php namespace Wordpress\Api;

use Guzzle\Service\Command\OperationCommand;

class Command extends OperationCommand {

    /**
     * @var array Possible fields to send in the request post field.
     */
    private $requestFields = array(
        'per_page',
        'page',
        'browse',
        'fields',
        'search',
        'tag',
        'author',
        'slug',
        'version',
    );

    /**
     * Build the request object.
     * 
     * @return void
     */
    public function build()
    {
        $requestFieldsCount = count($this->requestFields);
        $requestPost        = array();
        $this->request      = $this->client
            ->post(
                $this->getOperation()
                    ->getUri()
            );

        // Iterate through the requestFields array
        // and build the request post field.
        for ($x = 0; $x < $requestFieldsCount; ++$x)
        {
            $fieldName = $this->requestFields[$x];

            if ( ! is_null($this[$fieldName]))
            {
                $requestPost[$fieldName] = $this[$fieldName];
            }
        }

        $this->request->setPostField('action', $this['action']);
        $this->request->setPostField('request', serialize( (object) $requestPost));
    }

}