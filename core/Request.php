<?php

namespace Core;

class Request
{

    /**
     * @var array
     */
    public $data = [];
    /**
     * @var mixed
     */
    public $url;

    public function __construct()
    {
        $this->url = $this->removeQueryStringVariables(trim($_SERVER['QUERY_STRING']));

        $this->data = array_merge($_GET, $_POST);
        foreach ($this->data as $key => &$val) {
            if ($val == "") {
                unset($this->data[$key]);
            }
            $val = trim($val);
            $val = stripslashes($val);
            $val = htmlspecialchars($val);
        }
    }

    /**
     * @param $url URL
     * @return string
     */
    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }
}
