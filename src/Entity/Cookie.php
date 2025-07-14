<?php
namespace App\Entity;

class Cookie {
    public function __construct(private string $cookieName) {}
    
    public function store(mixed $data, int $cookieExpire = 0):void
    {
        setcookie(
            $this->cookieName, 
            serialize($data),
            $cookieExpire
        );
    }

    public function getData(): mixed
    {
        return (isset($_COOKIE[$this->cookieName])) ? unserialize($_COOKIE[$this->cookieName]) : null;
    }
}
