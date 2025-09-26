<?php namespace App\Services;
use GuzzleHttp\Client;
use Log;

class Http
{
    public static function httpRequest($resource)
    {
        $codeFlag = false;
        $repeatCnt = 1;
        do{
            $result = $resource;
            $result = json_decode(json_encode($result),true);

            $codeFlag = isset($result['code'])?true:false;
            if($codeFlag)
                $repeatCnt++;
        }while($codeFlag && $repeatCnt < TbkEnum::DEFAULT_HTTP_REPEAT);

        return $result;
    }
	
    public static function getRequest(string $url, array $parameters = [])
    {
        try {
            $response = self::Client()->request('GET', $url, [
                'query' => $parameters
            ]);

            return $response->getBody()->getContents();
            //$result = json_decode($response->getBody()->getContents(), true);

        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            Log::error('get请求出错：' . $e->getMessage());
            $result = ['code' => $e->getResponse()->getStatusCode()];
        }

        return $result;
    }

    public static function postRequest(string $url, array $parameters = [])
    {
        try {
            $response = self::Client()->request('POST', $url, [
                'json' => $parameters
            ]);
			
            //$result = json_decode($response->getBody()->getContents(), true);
            $result = $response->getBody();
			
			//dump($result);
			$result = $result->getContents();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            Log::error('post请求出错：' . $e->getMessage());
            $result = ['code' => $e->getResponse()->getStatusCode()];
        }

        return $result;
    }
	

    public static function getRequestWithHeader(string $url, array $header = [], array $parameters = [])
    {
        try {
            $response = self::Client()->request('GET', $url, [
                'headers' => $header,
                'query'    => $parameters
            ]);
            //$result = json_decode($response->getBody()->getContents(), true);
            $result = $response->getBody()->getContents();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            Log::error('post请求出错：' . $e->getMessage());
            $result = ['code' => $e->getResponse()->getStatusCode()];
        }

        return $result;
    }

    public static function postRequestWithHeader(string $url, array $header = [], array $parameters = [])
    {
        try {
            $response = self::Client()->request('POST', $url, [
                'headers' => $header,
                'json'    => $parameters
            ]);
            //$result = json_decode($response->getBody()->getContents(), true);
            $result = $response->getBody()->getContents();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            Log::error('post请求出错：' . $e->getMessage());
            $result = ['code' => $e->getResponse()->getStatusCode()];
        }

        return $result;
    }

    /**
     * @return Client
     */
    private static function Client()
    {
        return app(Client::class);
    }
}

