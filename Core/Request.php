<?php

namespace App\Core;

class Request
{
    protected string $uri;
    protected string $method;
    protected array $getProperties;
    protected array $postProperties;
    protected string $clientIp;
    protected string $clientAgent;
    protected static Request $requestInstance;
    private function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->getProperties = $_GET ?? [];
        $this->postProperties = $_POST ?? [];
        $this->clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
        $this->clientAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
    public static function create(): Request
    {
        if (!isset(self::$requestInstance))
            self::$requestInstance = new self();
        return self::$requestInstance;
    }
    public function getUri(): string
    {
        return $this->getUri();
    }
    public function getMethod(): string
    {
        return $this->method;
    }

    public function getGetProperties(): array
    {
        return $this->getProperties;
    }

    public function getPostProperties(): array
    {
        return $this->postProperties;
    }

    public function getClientIp(): string
    {
        return $this->clientIp;
    }

    public function getClientAgent(): string
    {
        return $this->clientAgent;
    }
}