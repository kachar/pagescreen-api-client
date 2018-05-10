<?php

namespace PageScreenClient\Entity;

class Capture
{
    public $id;
    public $token;
    /**
     * @var \PageScreenClient\Entity\Url
     */
    public $url;
    /**
     * @var \DateTime
     */
    public $requested_on;
}
