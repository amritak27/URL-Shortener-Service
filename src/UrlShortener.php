<?php

namespace App;

class UrlShortener {
    private $database;
    private $baseUrl = "http://short.est/";

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function encode($longUrl) {
        $shortCode = substr(md5($longUrl), 0, 6);
        $this->database->insertUrlMapping($shortCode, $longUrl);
        return $this->baseUrl . $shortCode;
    }

    public function decode($shortUrl) {
        $shortCode = str_replace($this->baseUrl, '', $shortUrl);
        return $this->database->getLongUrl($shortCode);
    }
}
