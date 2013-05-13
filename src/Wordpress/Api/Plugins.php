<?php namespace Wordpress\Api;

class Plugins {

    /**
     * Request object.
     *
     * @access protected
     * @var Request
     */
    protected $_request;

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

    public function getPlugin($slug)
    {
        return $this->_request
            ->reset()
            ->setUrl('http://api.wordpress.org/plugins/info/1.0/')
            ->setMethod('post')
            ->setPost(
                array(
                    'action'  => 'plugin_information',
                    'request' => (object) array(
                        'slug' => $slug,
                    )
                )
            )
            ->run();
    }

    public function getNew($page = 1)
    {
        return $this->_request
            ->reset()
            ->setUrl('http://api.wordpress.org/plugins/info/1.0/')
            ->setMethod('post')
            ->setPost(
                array(
                    'action'  => 'query_plugins',
                    'request' => (object) array(
                        'browse' => 'new',
                        'page'   => $page,
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