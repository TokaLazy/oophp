<?php

class Request
{
    private $param;

    public function __construct(array $param)
    {
        $this->param = $param;
    }

    public function existParam($key)
    {
        return (isset($this->param[$key]) && !empty($this->param[$key]));
    }

    public function getParam($key)
    {
        if ($this->existParam($key)) {
            return $this->param[$key];
        }

        throw new Exception("The param '$key' is not defined");
    }
}
