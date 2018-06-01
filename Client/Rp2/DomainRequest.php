<?php
namespace Sf\DfKumue\Client\Rp2;

/**
 * Class DomainRequest
 */
class DomainRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $command = 'bbDomain';

    /**
     * @var array
     */
    protected $allowedMethods = [
        'readEntry' => [
            'dn',
            'oeid',
            'seid',
            'return_array',
            'return_subdomain',
            'return_frontpage',
            'return_major',
            'return_settings',
            'return_handles',
            'return_staid',
            'return_nameserver',
            'return_spf',
            'return_webalizersettings',
            'return_phpini',
            'return_reseller_fields',
            'return_rpc',
            'return_limits'
        ],
        'readFrontpage' => [
            'oeid',
            'seid',
            'p_seid',
            'name',
            'return_array'
        ],
        'readHandles' => [
            'oeid',
            'seid',
            'p_seid',
            'dn',
            'return_staid',
            'return_array'
        ],
        'readNameserver' => [
            'oeid',
            'seid',
            'p_seid',
            'dn',
            'return_array',
            'return_spf'
        ],
        'readPhpini' => [
            'dn',
            'oeid',
            'seid',
            'p_seid',
            'return_array'
        ],
        'readSettings' => [
            'oeid',
            'seid',
            'p_seid',
            'dn',
            'return_array'
        ],
        'readSubdomain' => [
            'name',
            'oeid',
            'seid',
            'p_seid',
            'return_array', // undocumented, but working setting
            'return_nested'
        ],
        'readWebalizerSettings' => [
            'oeid',
            'seid',
            'p_seid',
            'dn',
            'return_array'
        ],
        'searchEntry' => [
            'pk',
            'name',
            'return_tree'
        ],
    ];

    /**
     * Preset a configuration to find domains by oeid
     *
     * @param int $oeid
     *
     * @return DomainRequest
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
     * Preset a configuration to find domains by seid
     *
     * @param int $seid
     *
     * @return DomainRequest
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
     * Preset a configuration to read all SubDomains
     *
     * @param int $oeid
     *
     * @return DomainRequest
     */
    public function readSubDomains($oeid = 0)
    {
        $this->method = 'readSubdomain';
        if (!empty($oeid)) {
            $this->setParameters([
                'oeid' => (int)$oeid,
                'return_array' => 1
            ]);
        }
        return $this;
    }

    /**
     * include SubDomains in request
     *
     * @return DomainRequest
     */
    public function includeSubDomains()
    {
        $this->addParameter('return_subdomain', '1');
        return $this;
    }
}
