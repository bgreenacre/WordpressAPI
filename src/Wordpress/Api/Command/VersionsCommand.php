<?php namespace Wordpress\Api\Command;

use Guzzle\Service\Command\OperationCommand;

class VersionsCommand extends OperationCommand {

    /**
     * Unset object properties when $this is destroyed.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->request);
        unset($this->result);
        unset($this->client);
        unset($this->operation);
        unset($this->validator);
    }

    /**
     * Builds the url and creates request object.
     * 
     * @return void
     */
    public function build()
    {
        // Set the base url.
        $this->client->setBaseUrl('http://wordpress.org');

        if ($this['package'] == 'core')
        {
            // Core is a specific uri.
            $this->request = $this->client
                ->get('/download/release-archive/');
        }
        else
        {
            // Create the uri dynamically based on
            // parameters sent to command.
            $this->request = $this->client
                ->get(
                    sprintf(
                        '/extend/%s/%s/developers/',
                        $this['package'],
                        $this['slug']
                    )
                );
        }

        // Reset base url in case the same client object
        // is used for future commands.
        $this->client->setBaseUrl('http://api.wordpress.org');
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

        if ($this['package'] == 'core')
        {
            // Find all url's containing zip files.
            preg_match_all(
                '#\<a([^\>]+)\>zip</a>#i',
                $body,
                $matches
            );

            foreach ($matches[1] as $url)
            {
                preg_match('/.*?href=(?:\'|"){1}(.*?wordpress-(.*?)\.zip)(?:\'|"){1}/', $url, $match);

                $versions[] = array(
                    'version' => $match[2],
                    'url'     => $match[1],
                );
            }
        }
        else
        {
            // Find all url's containing zip files.
            preg_match_all(
                '#href=(?:\'|"){1}(.*?' . preg_quote($this['slug']) . '\.?([0-9\.]+)?\.zip)(?:\'|"){1}#i',
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
        }

        $this->result = $versions;
    }

}