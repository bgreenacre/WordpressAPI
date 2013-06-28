<?php namespace Shc\Api\Model;

use Guzzle\Service\Resource\ResourceIterator;

class WordpressResultIterator extends ResourceIterator {

    protected function sendRequest()
    {
        if ( ! is_null($this->nextToken))
        {
            $this->command->set('page', $this->nextToken);
        }

        $result = $this->command->execute();

        $this->nextToken = $result->getCurrentPage() + 1;

        return isset($result['themes'])
            ? $result['themes']
            : isset($result['plugins'])
                ? $result['plugins']
                : array();
    }

}