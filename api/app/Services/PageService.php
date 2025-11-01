<?php

namespace App\Services;

use GuzzleHttp\Client;

class PageService
{
    private $baseUrl;
    private $ua;

    public function __construct()
    {
        $this->baseUrl = 'http://127.0.0.1:9030';
        $this->ua = 'Apifox/1.0.0 (https://www.apifox.cn)';
    }

    private function client(): Client
    {
        return app(Client::class);
    }

    private function postForm(string $path, array $data = [])
    {
        $resp = $this->client()->request('POST', rtrim($this->baseUrl, '/') . $path, [
            'headers' => [
                'User-Agent' => $this->ua,
            ],
            'form_params' => $data,
            'http_errors' => false,
            'timeout' => 10,
        ]);
        return (string)$resp->getBody();
    }

    public function getPage(string $id)
    {
        return $this->postForm('/get_page', ['id' => $id]);
    }

    public function startPage(string $id)
    {
        return $this->postForm('/start', ['id' => $id]);
    }

    public function delPage(string $id)
    {
        return $this->postForm('/del_page', ['id' => $id]);
    }

    public function setNotify(string $id, string $notify)
    {
        return $this->postForm('/set_notify', ['id' => $id, 'notify' => $notify]);
    }
}

