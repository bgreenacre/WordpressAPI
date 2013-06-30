<?php namespace Wordpress\Api;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;

class Response implements ResponseClassInterface, \ArrayAccess {

    /**
     * Contains response dataset from api.
     * 
     * @var array
     */
    protected $data;

    /**
     * Clear object properties when $this is destroyed.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->data = null;
    }

    /**
     * Create an object to handle response from api.
     * 
     * @param  OperationCommand $command The guzzle command for the api.
     * @return object                    Instance of this class.
     */
    public static function fromCommand(OperationCommand $command)
    {
        $data = @unserialize(
            (string) $command
                ->getResponse()
                ->getBody()
        );

        if (is_null($data))
        {
            $data = array();
        }

        return new self( (array) $data);
    }

    /**
     * Pass data from api response.
     * 
     * @param array $data Associative array of data.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the current page.
     * 
     * @return integer
     */
    public function getCurrentPage()
    {
        return isset($this['info']['page'])
            ? $this['info']['page']
            : 1;
    }

    /**
     * Get page count.
     * 
     * @return integer
     */
    public function getPageCount()
    {
        return isset($this['info']['pages'])
            ? (int) $this['info']['pages']
            : 0;
    }

    /**
     * Get the total results count.
     * 
     * @return integer
     */
    public function getTotalResults()
    {
        return isset($this['info']['results'])
            ? (int) $this['info']['results']
            : 0;
    }

    /**
     * Get the error message from the response object.
     * 
     * @return string
     */
    public function getError()
    {
        return isset($this['error'])
            ? $this['error']
            : null;
    }

    /**
     * Check if a index from the data array exists.
     * 
     * @param  string $key Index wanted.
     * @return boolean     True is index exists else false.
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Get an index from the data array.
     * 
     * @param  string $key Index to get.
     * @return mixed
     */
    public function offsetGet($key)
    {
        return (array_key_exists($key, $this->data))
            ? (array) $this->data[$key]
            : null;
    }

    /**
     * Unset an index in the data array.
     * 
     * @param  string $key Index to unset.
     * @return void
     */
    public function offsetUnset($key)
    {
        if (array_key_exists($key, $this->data))
        {
            unset($this->data[$key]);
        }
    }

    /**
     * Set value to an index in the data array.
     * 
     * @param  string $key   Index to set value to
     * @param  mixed  $value Value to set
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->data[$key] = $value;
    }

}
