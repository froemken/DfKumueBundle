<?php
namespace Sf\DfKumue\Client\Rp2;

/**
 * Class OrderRequest
 */
class OrderRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $command = 'bbOrder';

    /**
     * @var array
     */
    protected $allowedMethods = [
        'getTariff' => [
            'oeid',
            'return_active',
            'return_dispo',
        ],
        'readAccountAdress' => [
            'aeid',
            'return_array',
            'diff_ignore_email',
        ],
        'readAccountEntry' => [
            'aeid',
            'ceid',
            'oeid',
            'accnr',
            'settlement_aeid',
            'paid',
            'posting_type',
            'return_array',
            'return_adress',
            'return_adress_cus',
            'return_items',
            'return_customer',
            'return_customer_overview',
            'return_cusnr',
            'return_ordnr',
            'return_payinfo',
            'return_payment',
            'return_settlements',
        ],
        'readAccountItem' => [
            'aiid',
            'aeid',
            'odid',
            'return_entry_date',
            'return_entry_accnr',
            'return_array',
        ],
        'readDisposition' => [],
        'readDomCon' => [
            'oeid',
        ],
        'readEntry' => [
            'oeid',
            'ceid',
            'ordnr',
            'scale',
            'return_unfree',
            'return_array',
            'return_dispositions',
            'return_dispositions_prices',
            'return_msg',
            'return_customer',
            'return_customer_overview',
            'return_account_entrys',
            'return_settlements',
            'return_adress',
            'return_limits',
            'return_load',
            'return_max_only',
            'return_domcon',
            'return_active',
            'return_scale',
            'return_domain',
            'return_tariff',
        ],
        'readLimits' => [
            'oeid',
            'exclude_odid',
            'add',
            'reduce',
            'return_max_only',
            'return_no_used_webspace',
        ],
        'readLimitsWarn' => [
            'oeid',
        ],
        'readLoad' => [
            'oeid',
            'return_array',
        ],
        'readTariffs' => [
            'oeid',
            'ceid',
            'ordnr',
            'scale',
        ],
        'readTariffsOverview' => [],
        'searchAccountEntry' => [
            'paid',
            'accnr',
            'return_tree',
        ],
        'searchEntry' => [
            'ordnr',
            'can_account',
            'free_date',
            'tariff_peid',
            'return_tree',
        ],
    ];

    /**
     * Preset a configuration to find dispositions
     *
     * @param int $oeid
     *
     * @return OrderRequest
     */
    public function readOrder($oeid)
    {
        $this->method = 'readEntry';
        $this->setParameters([
            'oeid' => (int)$oeid,
            'return_array' => '1'
        ]);
        return $this;
    }

    /**
     * Pre configures configuration to return dispositions, too
     *
     * @return OrderRequest
     */
    public function includeDispositions()
    {
        $this->addParameter('return_dispositions', '1');
        return $this;
    }
}
