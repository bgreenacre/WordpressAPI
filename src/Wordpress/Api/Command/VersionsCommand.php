<?php namespace Wordpress\Api\Command;

use Guzzle\Service\Command\OperationCommand;

class VersionsCommand extends OperationCommand {

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

    public function process()
    {
        $versions = array();
        $body = (string) $this->request->getResponse()->getBody();

        preg_match_all(
            '#\<a(?:.*?)href=(?:\'|")+(.*?' . preg_quote($this['slug']) . '\.?([0-9\.]+)?\.zip)(?:\'|")+.*?\>#i',
            $body,
            $matches
        );

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