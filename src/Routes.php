<?php

namespace App;

class Routes {
    private $urlShortener;

    public function __construct() {
        $database = new Database();
        $this->urlShortener = new UrlShortener($database);
    }

    public function handleRequest() {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        header('Content-Type: application/json; charset=utf-8');
        if ($uri === '/' && $method === 'GET') {
            $this->showEndpoints();
        } elseif ($uri === '/encode' && $method === 'POST') {
            $this->encode();
        } elseif ($uri === '/decode' && $method === 'POST') {
            $this->decode();
        } else {
            $this->notFound();
        }
    }

    private function showEndpoints() {
        echo json_encode([
            'message' => 'URL Shortener. Available endpoints:',
            'endpoints' => [
                'Encode URL' => [
                    'url' => '/encode',
                    'method' => 'POST',
                    'request' => '{ "url": "http://example.com" }',
                    'response' => '{ "shortUrl": "http://short.est/abc123" }'
                ],
                'Decode URL' => [
                    'url' => '/decode',
                    'method' => 'POST',
                    'request' => '{ "url": "http://short.est/abc123" }',
                    'response' => '{ "longUrl": "http://example.com" }'
                ]
            ]
        ]);
    }

    private function encode() {
        $data = json_decode(file_get_contents('php://input'), true);
        $longUrl = $data['url'] ?? '';
        $shortUrl = $this->urlShortener->encode($longUrl);
        echo json_encode(['shortUrl' => $shortUrl]);
    }

    private function decode() {
        $data = json_decode(file_get_contents('php://input'), true);
        $shortUrl = $data['url'] ?? '';
        $longUrl = $this->urlShortener->decode($shortUrl);
        echo json_encode(['longUrl' => $longUrl]);
    }

    private function notFound() {
        http_response_code(404);
        echo json_encode(['message' => 'Not Found']);
    }
}
