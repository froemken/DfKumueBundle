<?php
namespace Sf\DfKumue\Client\Rp2;

/**
 * Class SessionRequest
 */
class SessionRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $command = 'bfSession';

    /**
     * @var string
     */
    protected $method = 'createAuthToken';

    /**
     * @var array
     */
    protected $allowedMethods = [
        'createAuthToken' => [
            'name',
            'password',
            'ip',
            'prepare_session',
        ]
    ];

    /**
     * SessionRequest constructor.
     *
     * @param int $orderNumber
     * @param int $customerNumber
     */
    public function __construct($orderNumber, $customerNumber = 0) {
        $this->customerNumber = (int)$customerNumber;
        parent::__construct($orderNumber);
    }

    /**
     * Preset a configuration to find one FTP account by username
     *
     * @return void
     */
    public function prepareSession()
    {
        $this->setParameters([
            'prepare_session' => 1
        ]);
    }
}
