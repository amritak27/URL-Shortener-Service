<?php

use PHPUnit\Framework\TestCase;
use App\UrlShortener;
use App\Database;

class UrlShortenerTest extends TestCase {
    private $database;

    protected function setUp(): void {
        $this->database = new Database();
    }

    public function testEncodeDecode() {
        $urlShortener = new UrlShortener($this->database);
        $longUrl = "https://www.example.com";
        $shortUrl = $urlShortener->encode($longUrl);
        $decodedUrl = $urlShortener->decode($shortUrl);

        $this->assertEquals($longUrl, $decodedUrl);
    }
}