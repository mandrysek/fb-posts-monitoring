<?php
namespace App\Services\Facebook;

use Facebook\PersistentData\PersistentDataInterface;

class PersistentDataHandler implements PersistentDataInterface
{
    private $sessionPrefix = 'FBS_';

    public function get($key)
    {
        return \Session::get($this->sessionPrefix . $key);
    }

    public function set($key, $value)
    {
        \Session::put($this->sessionPrefix . $key, $value);
    }

}