<?php
namespace UsaRugbyStats\RemoteDataSync\DataProvider;

use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Account\Entity\Account;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Http\Client\Adapter\Curl;

class JsonWebServiceProvider implements DataProviderInterface
{
    protected $webServiceEndpoint = '';

    protected $httpClient;

    public function syncTeam(Team $t)
    {
        $request = new Request();
        $request->setUri(str_replace(':remoteId', $t->getRemoteId(), $this->getWebServiceEndpoint()));
        $request->setMethod(Request::METHOD_GET);

        $response = $this->getHttpClient()->send($request);
        if ( ! $response->isSuccess() ) {
            return false;
        }

        return json_decode($response->getBody(), true);
    }

    public function syncPlayer(Account $u)
    {
    }

    public function getWebServiceEndpoint()
    {
        return $this->webServiceEndpoint;
    }

    public function setWebServiceEndpoint($uri)
    {
        $this->webServiceEndpoint = $uri;

        return $this;
    }

    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;

        return $this;
    }

    public function getHttpClient()
    {
        if ( empty($this->httpClient) ) {
            $this->httpClient = new Client();
            $this->httpClient->setAdapter(new Curl());
        }

        return $this->httpClient;
    }

}
