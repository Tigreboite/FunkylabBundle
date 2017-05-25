<?php

namespace Tigreboite\FunkylabBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class FunkylabService.
 */
class FunkylabService
{
    private $data;
    private $session;
    private $session_key = 'funkylab_profiler';

    /**
     * FunkylabService constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->data = $this->session->get($this->session_key, []);
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
        $this->updateSession();
    }

    public function updateSession()
    {
        $this->session->set($this->session_key, $this->data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    public function clear()
    {
        $this->session->set($this->session_key, []);
        $this->data = [];
    }
}
