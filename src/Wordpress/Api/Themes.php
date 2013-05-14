<?php namespace Wordpress\Api;

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

    public function getNew($page = 1)
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
                        'per_page' => 50,
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