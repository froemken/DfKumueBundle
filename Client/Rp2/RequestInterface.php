<?php
namespace Sf\DfKumue\Client\Rp2;

/**
 * Interface RequestInterface
 */
interface RequestInterface
{
    /**
     * @return string
     */
    public function getCommand();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @return int
     */
    public function getOrderNumber();

    /**
     * @return int
     */
    public function getCustomerNumber();

    /**
     * Check, if request is valid
     *
     * @return bool
     */
    public function isValidRequest();
}
