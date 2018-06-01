<?php
namespace Sf\DfKumue\Client\Rp2;

/**
 * Class AbstractRequest
 */
class AbstractRequest implements RequestInterface
{
    /**
     * @var string
     */
    protected $command = '';

    /**
     * @var string
     */
    protected $method = '';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var int
     */
    protected $orderNumber = 0;

    /**
     * @var int
     */
    protected $customerNumber = 0;

    /**
     * @var array
     */
    protected $allowedMethods = [];

    /**
     * AbstractRequest constructor.
     *
     * @param int $orderNumber
     */
    public function __construct($orderNumber) {
        $this->orderNumber = (int)$orderNumber;
    }

    /**
     * Returns the command
     *
     * @return string $command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Returns the method
     *
     * @return string $method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the method
     *
     * @param string $method
     *
     * @return void
     */
    public function setMethod($method)
    {
        $this->method = (string)$method;
    }

    /**
     * Returns the parameters
     *
     * @return array $parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Sets the parameters
     *
     * @param array $parameters
     *
     * @return void
     */
    public function setParameters($parameters)
    {
        $this->parameters = (array)$parameters;
    }

    /**
     * Add parameter
     *
     * @param string $parameter
     * @param string $value
     *
     * @return void
     */
    public function addParameter($parameter, $value)
    {
        $this->parameters[$parameter] = (string)$value;
    }

    /**
     * Get order number
     *
     * @return int
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Get customer number
     *
     * @return int
     */
    public function getCustomerNumber()
    {
        return $this->customerNumber;
    }

    /**
     * Sets the customerNumber
     *
     * @param int $customerNumber
     *
     * @return void
     */
    public function setCustomerNumber($customerNumber)
    {
        $this->customerNumber = (int)$customerNumber;
    }

    /**
     * Check, if request is valid
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function isValidRequest()
    {
        $isValid = true;
        if ($this->getMethod() === '') {
            throw new \Exception('RP2 method can not be empty');
        }
        if (!array_key_exists($this->getMethod(), $this->allowedMethods)) {
            $isValid = false;
        }
        if (!empty($this->parameters)) {
            $diff = array_diff(array_keys($this->getParameters()), $this->allowedMethods[$this->getMethod()]);
            if (!empty($diff)) {
                $isValid = false;
            }
        }
        return $isValid;
    }
}
