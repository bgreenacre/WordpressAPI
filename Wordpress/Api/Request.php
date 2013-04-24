<?php namespace Wordpress\Api;

class Request {

    protected $_query = array();
    protected $_post = array();
    protected $_method = 'post';
    protected $_url;
    protected $_action;
    protected $_response;

    public function request()
    {
        $url = $this->getAbsoluteUrl();

        $ch = curl_init($url);

        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_HEADER] = false;

        if ($this->getMethod() == 'post')
        {
            $options[CURLOPT_POST] = true;
        }

        if ($post = $this->getPost())
        {
            $post['body'] = serialize($post['body']);
            
            $options[CURLOPT_POSTFIELDS] = $post;
        }

        curl_setopt_array($ch, $options);

        $body = curl_exec($ch);

        // Get the response information
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($body === FALSE)
        {
            $error = curl_error($curl);
        }

        // Close the connection
        curl_close($curl);

        if (isset($error))
        {
            throw new \Exception(
                sptrinf(
                    'Error fetching remote %s [ status %s ] %s',
                    $request->url(),
                    $code,
                    $error
                )
            );
        }

        $this->_response = $body;

        return $this;
    }

    public function run()
    {
        $this->request();

        $body = $this->getResponse();

        return ($body) ? json_decode($body, true) : null;
    }

    public function getResponse()
    {
        return $this->_response;
    }

    public function setResponse($response)
    {
        if ( ! is_string($response))
        {
            throw new \InvalidArgumentException(
                'setResponse argument is not a string.'
            );
        }

        $this->_response = $response;

        return $this;
    }

    public function getAbsoluteUrl()
    {
        $url = $this->getUrl();
        $url .= rtrim($url, '/') . $this->getAction();

        if ($query = $this->getQuery())
        {
            $url .= '?' . http_build_query($query, null, '&');
        }

        return $url;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function setUrl($url)
    {
        $this->_url = $url;

        return $this;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function setAction($action)
    {
        $this->_action = $action;

        return $this;
    }

    public function getPost()
    {
        return $this->_post;
    }

    public function setPost(array $post)
    {
        $this->_post = $post;

        return $this;
    }

    public function getQuery()
    {
        return $this->_query;
    }

    public function setQuery(array $query)
    {
        $this->_query = $query;

        return $this;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function setMethod($method)
    {
        $this->_method = $method;

        return $this;
    }

}