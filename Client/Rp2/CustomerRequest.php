<?php
namespace Sf\DfKumue\Client\Rp2;

/**
 * Class CustomerRequest
 */
class CustomerRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $command = 'bbCustomer';

    /**
     * @var array
     */
    protected $allowedMethods = [
        'readAdress' => [
            'ceid',
            'typ',
            'return_array',
            'return_country'
        ],
        'readCountry' => [
            'ccid',
            'preset',
            'contraction',
            'active',
            'return_array',
            'return_lang'
        ],
        'readDiscount' => [
            'cdid',
            'preset',
            'return_array',
            'return_lang'
        ],
        'readEntry' => [
            'ceid',
            'uid',
            'newsletter',
            'return_array',
            'return_policy',
            'return_adress',
            'return_adress_country',
            'return_payinfo',
            'return_payment',
            'return_handles',
            'return_orders',
            'return_limits',
            'return_account_entrys',
            'return_settlements',
            'return_user',
            'return_overview'
        ],
        'readHandles' => [
            'ceid'
        ],
        'readPayinfo' => [
            'return_array',
            'return_payment',
        ],
        'readPayment' => [
        ],
        'readTitle' => [
            'ctid',
            'value',
            'return_array',
            'return_lang'
        ],
        'searchEntry' => [
            'cusnr',
            'last_name',
            'first_name',
            'email',
            'active',
            'return_tree'
        ],
    ];

    /**
     * Preset a configuration to find a customer by ceid
     *
     * @param int $ceid
     *
     * @return CustomerRequest
     */
    public function readByCeid($ceid)
    {
        $this->method = 'readEntry';
        $this->addParameter('ceid', (int)$ceid);
        return $this;
    }

    /**
     * Preset a configuration to find an customer address
     *
     * @param int $ceid
     *
     * @return CustomerRequest
     */
    public function readCustomerAddress($ceid)
    {
        $this->method = 'readAdress';
        $this->addParameter('ceid', (int)$ceid);
        $this->addParameter('typ', 'cus');
        return $this;
    }

    /**
     * Preset a configuration to find an invoice address
     *
     * @param int $ceid
     *
     * @return CustomerRequest
     */
    public function readInvoiceAddress($ceid)
    {
        $this->method = 'readAdress';
        $this->addParameter('ceid', (int)$ceid);
        $this->addParameter('typ', 'inv');
        return $this;
    }

    /**
     * Include addresses
     *
     * @return CustomerRequest
     */
    public function includeAddresses()
    {
        $this->addParameter('return_adress', '1');
        return $this;
    }
}
