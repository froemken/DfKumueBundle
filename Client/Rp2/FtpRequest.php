<?php
namespace Sf\DfKumue\Client\Rp2;

/**
 * Class FtpRequest
 */
class FtpRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $command = 'bbFtp';

    /**
     * @var array
     */
    protected $allowedMethods = [
        'createAdminAccess' => [
            'seid'
        ],
        'readEntry' => [
            'username',
            'oeid',
            'seid',
            'return_array',
        ]
    ];

    /**
     * Preset a configuration to find one FTP account by username
     *
     * @param $username
     *
     * @return void
     */
    public function readOneByUsername($username)
    {
        $this->method = 'readEntry';
        $this->setParameters([
            'username' => htmlspecialchars($username),
            'return_array' => 0
        ]);
    }

    /**
     * Preset a configuration to find FTP accounts by oeid
     *
     * @param int $oeid
     *
     * @return void
     */
    public function readByOeid($oeid)
    {
        $this->method = 'readEntry';
        $this->setParameters([
            'oeid' => (int)$oeid,
            'return_array' => 1
        ]);
    }

    /**
     * Preset a configuration to find FTP accounts by seid
     *
     * @param int $seid
     *
     * @return void
     */
    public function readBySeid($seid)
    {
        $this->method = 'readEntry';
        $this->setParameters([
            'seid' => (int)$seid,
            'return_array' => 1
        ]);
    }
}
