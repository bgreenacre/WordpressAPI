<?php namespace Wordpress\Api\Model;

use Guzzle\Service\Resource\ResourceIterator;

class WordpressResultIterator extends ResourceIterator {

    /**
     * Build new command and execute with pagination
     * tokens.
     * 
     * @return array Array of result resources.
     */
    protected function sendRequest()
    {
        if ( ! is_null($this->nextToken))
        {
            $this->command->set('page', $this->nextToken);
        }

        $result = $this->command->execute();

        // Increment page.
        $this->nextToken = $result->getCurrentPage() + 1;

        if (isset($result['themes']))
        {
            return $result['themes'];
        }
        elseif (isset($result['plugins']))
        {
            return $result['plugins'];
        }
        else
        {
            return array();
        }
    }

}