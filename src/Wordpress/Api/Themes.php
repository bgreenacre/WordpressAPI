<?php namespace Wordpress\Api;

use Goutte\Client;

class Themes {

    /**
     * Request object.
     *
     * @access protected
     * @var Request
     */
    protected $_request;

    protected $fields = array(
        'description' => true,
        'sections' => false,
        'tested' => true,
        'requires' => true,
        'rating' => true,
        'downloaded' => true,
        'downloadlink' => true,
        'last_updated' => true,
        'homepage' => true,
        'tags' => true,
        'num_ratings' => true,
    );

    /**
     * Construct object.
     *
     * @access public
     * @param Request $request Request object used to handle http request.
     * @return void
     */
    public function __construct(Request $request = null)
    {
        if ($request === null)
        {
            $request = new Request();
        }

        $this->setRequest($request);
    }

    public function getTheme($slug)
    {
        return $this->_request
            ->reset()
            ->setUrl('http://api.wordpress.org/themes/info/1.0/')
            ->setMethod('post')
            ->setPost(
                array(
                    'action'  => 'theme_information',
                    'request' => (object) array(
                        'slug' => $slug,
                    )
                )
            )
            ->run();
    }

    public function getVersions($slug)
    {
        $url = sprintf('https://wordpress.org/extend/themes/%s/developers/', $slug);
        $client = new Client();

        $client->getClient()->setConfig(
            array(
                'curl.options' => array(
                    CURLOPT_TIMEOUT => 60,
                )
            )
        );

        $page = $client->request('GET', $url);

        $versionsFiltered = $page->filter('a')->each(function($node, $i) use ($slug)
        {
            preg_match(
                '#[^\s]+(' . preg_quote($slug) . ')\.?([0-9\.]+)\.zip$#',
                $node->attr('href'),
                $matches
            );

            if (count($matches) > 0)
            {
                return array(array_pop($matches), $matches[0]);
            }
            else
            {
                return false;
            }
        });

        $versionsFiltered = array_filter($versionsFiltered, function($value)
        {
            return (false !== $value);
        });

        $versions = array();

        foreach ($versionsFiltered as $version)
        {
            $versions[$version[0]] = $version[1];
        }

        return $versions;
    }

    public function getNew($page = 1, $perPage = 50)
    {
        return $this->_request
            ->reset()
            ->setUrl('http://api.wordpress.org/themes/info/1.0/')
            ->setMethod('post')
            ->setPost(
                array(
                    'action'  => 'query_themes',
                    'request' => (object) array(
                        'page'     => $page,
                        'per_page' => (int) $perPage,
                        'fields'   => null,
                        'search'   => '',
                    )
                )
            )
            ->run();
    }

    public function setRequest(Request $request)
    {
        $this->_request = $request;

        return $this;
    }

    public function getRequest()
    {
        return $this->_request;
    }

}