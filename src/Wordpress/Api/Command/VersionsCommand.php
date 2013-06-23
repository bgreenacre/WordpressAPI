<?php namespace Wordpress\Api\Command;

use Guzzle\Service\Command\OperationCommand;

class VersionsCommand extends OperationCommand {

    /**
     * Builds the url and creates request object.
     * 
     * @return void
     */
    public function build()
    {
        $this->client->setBaseUrl('http://wordpress.org');

        $this->request = $this->client
            ->get(
                sprintf(
                    '/extend/%s/%s/developers/',
                    $this['package'],
                    $this['slug']
                )
            );
    }

    /**
     * Process the response. Find all the version urls
     * and build a nice array containing versions and
     * url's to where the zip can be downloaded.
     * 
     * @return void
     */
    public function process()
    {
        $versions = array();
        $body = (string) $this->request->getResponse()->getBody();

        // Find all url's containing zip files.
        preg_match_all(
            '#\<a(?:.*?)href=(?:\'|")+(.*?' . preg_quote($this['slug']) . '\.?([0-9\.]+)?\.zip)(?:\'|")+.*?\>#i',
            $body,
            $matches
        );

        // Build an array of found versions
        if (isset($matches[1]))
        {
            foreach ($matches[1] as $key => $url)
            {
                if (empty($matches[2][$key]))
                {
                    continue;
                }

                $versions[] = array(
                    'version' => $matches[2][$key],
                    'url'     => $url,
                );
            }
        }

        $this->result = $versions;
    }

}