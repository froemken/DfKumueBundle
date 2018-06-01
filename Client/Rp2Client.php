<?php
namespace Sf\DfKumue\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Sf\DfKumue\Client\Exception\Rp2Exception;
use Sf\DfKumue\Client\Exception\StatusCodeException;
use Sf\DfKumue\Client\Rp2\RequestInterface;
use Sf\DfKumue\Client\Rp2\SessionRequest;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class Rp2Client
 *
 * This is currently only a try
 */
class Rp2Client
{
    /**
     * @var string
     */
    protected $authUri = 'https://%d.premium-admin.eu/rpc/auth';

    /**
     * @var string
     */
    protected $callUri = 'https://%d.premium-admin.eu/%s/rpc/call';

    /**
     * @var string
     */
    protected $passwordForRP2 = '';

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var int
     */
    protected $orderNumber = 0;

    /**
     * @var string
     */
    protected $sessionId = '';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var CookieJar
     */
    protected $cookieJar;

    /**
     * Set URI to authenticate at RP2
     *
     * @param string $authUri
     *
     * @return void
     */
    public function setAuthUri($authUri)
    {
        $this->authUri = $authUri;
    }

    /**
     * Set URI for new call to RP2
     *
     * @param string $callUri
     *
     * @return void
     */
    public function setCallUri($callUri)
    {
        $this->callUri = $callUri;
    }

    /**
     * Set RP2 admin password
     *
     * @param string $passwordForRP2
     *
     * @return void
     */
    public function setPasswordForRP2($passwordForRP2)
    {
        $this->passwordForRP2 = $passwordForRP2;
    }

    /**
     * Set Logger
     *
     * @param Logger $logger
     *
     * @return void
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Process request
     *
     * @param RequestInterface $request
     *
     * @return array
     *
     * @throws \Exception
     */
    public function processRequest(RequestInterface $request)
    {
        if (!$request->isValidRequest()) {
            $this->logger->error('Invalid request: ' . $request->getCommand() . '::' . $request->getMethod());
            return [];
        }

        try {
            if (!$this->sessionId || $this->orderNumber !== $request->getOrderNumber()) {
                $this->reset();
                $this->orderNumber = $request->getOrderNumber();
                $this->authenticate($request->getOrderNumber());
            }

            $response = $this->call(
                sprintf($this->callUri, $this->orderNumber, $this->sessionId),
                [
                    'method' => $request->getCommand() . '::' . $request->getMethod(),
                    'params' => $request->getParameters()
                ]
            );
            $this->checkForErrors($response);
        } catch (ConnectException $e) {
            throw new \Exception('Guzzle Network Error. Can not access internet. Message: ' . $e->getMessage());
        } catch (RequestException $e) {
            throw new \Exception('Guzzle Network Error. Timeout or DNS. Message: ' . $e->getMessage());
        } catch (StatusCodeException $e) {
            throw new \Exception('RP2 Status Code Error. Message: ' . $e->getMessage());
        } catch (Rp2Exception $e) {
            throw new \Exception('RP2 Error. Message: ' . $e->getMessage());
        }

        return $response['Return'];
    }

    /**
     * Authenticate at server
     * If valid, a session ID will be set
     * Hint: Do not add customerNumber for method ->authenticate()
     *
     * @param int $orderNumber
     * @param int $customerNumber
     *
     * @return void
     */
    protected function authenticate($orderNumber, $customerNumber = 0)
    {
        $response = $this->call(
            sprintf($this->authUri, $this->orderNumber),
            [
                'method' => 'auth',
                'params' => [
                    'user' => $this->getUsername($orderNumber, $customerNumber),
                    'pass' => $this->passwordForRP2
                ]
            ]
        );
        $this->checkForErrors($response);
        if (isset($response['sid'])) {
            $this->sessionId = $response['sid'];
        }
    }

    /**
     * Start a request to RPC Server
     *
     * @param string $uri
     * @param array $data
     *
     * @return array
     *
     * @throws StatusCodeException
     * @throws Rp2Exception
     */
    protected function call($uri, array $data = array())
    {
        if ($this->client === null) {
            $this->client = new Client();
        }
        $response = $this->client->request('POST', $uri, [
            'cookies' => $this->getCookieJar(),
            'form_params' => [
                'data' => json_encode($data)
            ]
        ]);
        if ($response->getStatusCode() === 200) {
            $response = json_decode((string)$response->getBody(), 1);
            if ($response === null) {
                throw new Rp2Exception('Response is empty. Please check if cookie was set successfully', 1493741531);
            }
        } else {
            throw new StatusCodeException('Status code differs from 200. Something went wrong', 1493740446);
        }
        return $response;
    }

    /**
     * Get Cookie
     *
     * @return CookieJar
     */
    protected function getCookieJar()
    {
        if ($this->cookieJar === null) {
            $this->cookieJar = new CookieJar();
        }
        return $this->cookieJar;
    }

    /**
     * check result from RP2
     *
     * @param array $response
     *
     * @return bool
     *
     * @throws Rp2Exception
     */
    protected function checkForErrors(array $response)
    {
        if (is_array($response['Error'])) {
            foreach ($response['Error'] as $error) {
                // $error consists of "msg" and "typ"
                if ($error['typ'] !== 'ok') {
                    throw new Rp2Exception($error['msg'], 1493740827);
                }
            }
        }
        return true;
    }

    /**
     * Returns the username
     *
     * @param int $orderNumber
     * @param int $customerNumber
     *
     * @return string $username
     */
    private function getUsername($orderNumber, $customerNumber = 0)
    {
        $username = 'admin-' . $orderNumber;
        if (!empty($customerNumber)) {
            $username .= ':' . $customerNumber;
        }
        return $username;
    }

    /**
     * Get OneTime Auth Token
     * As we need RP2 password for this method, we have to implement it in this class
     *
     * @param SessionRequest $sessionRequest
     *
     * @return string|array
     */
    public function getOneTimeAuthToken(SessionRequest $sessionRequest)
    {
        $this->reset(); // delete cookie as this must be a new request
        $sessionRequest->setParameters(array_merge(
            $sessionRequest->getParameters(),
            [
                'name' => $this->getUsername(
                    $sessionRequest->getOrderNumber(),
                    $sessionRequest->getCustomerNumber()
                ),
                'password' => $this->passwordForRP2
            ]
        ));
        return $this->processRequest($sessionRequest);
    }

    /**
     * Reset RP2 client for new connects
     * Useful to switch to another server
     *
     * @return void
     */
    public function reset()
    {
        $this->sessionId = '';
        $this->cookieJar = null;
    }
}
