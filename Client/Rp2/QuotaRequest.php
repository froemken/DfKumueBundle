<?php
namespace Sf\DfKumue\Client\Rp2;

/**
 * Class QuotaRequest
 */
class QuotaRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $command = 'bbQuota';

    /**
     * @var array
     */
    protected $allowedMethods = [
        'createAdminAccess' => [
            'seid'
        ],
        'readEntry' => [
            'pfad',
            'oeid',
            'seid',
            'uid',
            'return_array',
            'return_ssh',
            'return_base',
        ]
    ];

    /**
     * Preset a configuration to find SSH accounts by oeid
     *
     * @param int $oeid
     *
     * @return QuotaRequest
     */
    public function readByOeid($oeid)
    {
        $this->method = 'readEntry';
        $this->setParameters([
            'oeid' => (int)$oeid,
            'return_array' => 1
        ]);
        return $this;
    }

    /**
     * Preset a configuration to find FTP accounts by seid
     *
     * @param int $seid
     *
     * @return QuotaRequest
     */
    public function readBySeid($seid)
    {
        $this->method = 'readEntry';
        $this->setParameters([
            'seid' => (int)$seid,
            'return_array' => 1
        ]);
        return $this;
    }

    /**
     * Presets the configuration to return SSH accounts, too
     *
     * @return QuotaRequest
     */
    public function includeSshAccounts()
    {
        $this->addParameter('return_ssh', '1');
        return $this;
    }
}
