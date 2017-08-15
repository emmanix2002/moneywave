<?php

namespace Emmanix2002\Moneywave\Enum;

class Banks
{
    const ACCESS_BANK = '044';
    const ACCESS_MOBILE = '323';
    const AFRIBANK = '014';
    const ASO_SAVINGS_AND_LOANS = '401';
    const DIAMOND_BANK = '063';
    const ECOBANK = '050';
    const ECOBANK_MOBILE = '307';
    const ENTERPRISE_BANK = '084';
    const FCMB = '214';
    const FIDELITY_BANK = '070';
    const FIRST_BANK = '011';
    const FIRST_BANK_MOBILE = '309';
    const GTBANK = '058';
    const GTBANK_MOBILE_MONEY = '315';
    const HERITAGE_BANK = '030';
    const KEYSTONE_BANK = '082';
    const PARKWAY = '311';
    const PAYCOM = '305';
    const SKYE_BANK = '076';
    const STANBIC_IBTC = '221';
    const STANBIC_MOBILE = '304';
    const STANDARD_CHARTERED_BANK = '068';
    const STERLING_BANK = '232';
    const UBA = '033';
    const UNION_BANK = '032';
    const UNITY_BANK = '215';
    const WEMA_BANK = '035';
    const ZENITH_BANK = '057';
    const ZENITH_MOBILE = '322';

    const BANK_CODES = [
        self::ACCESS_BANK             => 'Access Bank Nigeria',
        self::ACCESS_MOBILE           => 'Access Mobile',
        self::AFRIBANK                => 'Afribank Nigeria Plc',
        self::ASO_SAVINGS_AND_LOANS   => 'Aso Savings And Loans',
        self::DIAMOND_BANK            => 'Diamond Bank Plc',
        self::ECOBANK                 => 'Ecobank Nigeria Plc',
        self::ENTERPRISE_BANK         => 'Enterprise Bank Limited',
        self::ECOBANK_MOBILE          => 'Ecobank Mobile',
        self::FIRST_BANK_MOBILE       => 'Fbn Mobile',
        self::FIDELITY_BANK           => 'Fidelity Bank Plc',
        self::FIRST_BANK              => 'First Bank Plc',
        self::FCMB                    => 'First City Monument Bank Plc',
        self::GTBANK                  => 'Gtbank Plc',
        self::GTBANK_MOBILE_MONEY     => 'Gtbank Mobile Money',
        self::HERITAGE_BANK           => 'Heritage Bank',
        self::KEYSTONE_BANK           => 'Keystone Bank Plc',
        self::PAYCOM                  => 'Paycom',
        self::PARKWAY                 => 'Parkway',
        self::SKYE_BANK               => 'Skye Bank Plc',
        self::STANBIC_IBTC            => 'Stanbic Ibtc Bank Plc',
        self::STANDARD_CHARTERED_BANK => 'Standard Chartered Bank Nigeria Limited',
        self::STERLING_BANK           => 'Sterling Bank Plc',
        self::STANBIC_MOBILE          => 'Stanbic Mobile',
        self::UNION_BANK              => 'Union Bank Of Nigeria Plc',
        self::UBA                     => 'United Bank For Africa Plc',
        self::UNITY_BANK              => 'Unity Bank Plc',
        self::WEMA_BANK               => 'Wema Bank Plc',
        self::ZENITH_BANK             => 'Zenith Bank Plc',
        self::ZENITH_MOBILE           => 'Zenith Mobile',
    ];

    /**
     * Returns an associative array containing of bank codes in the form: [code => bank_name].
     *
     * @return array
     */
    public static function getBanks(): array
    {
        return self::BANK_CODES;
    }

    /**
     * Returns an associative array containing of bank codes in the form: [code => bank_name].
     *
     * @return array
     */
    public static function getSupportedBanks(): array
    {
        return array_merge(
            [self::ACCESS_BANK => self::BANK_CODES[self::ACCESS_BANK]],
            self::getSupportedBanksForInternetBanking()
        );
    }

    /**
     * Returns the list of support banks for internet banking billing.
     *
     * @return array
     */
    public static function getSupportedBanksForInternetBanking(): array
    {
        $codes = [self::GTBANK, self::UBA, self::DIAMOND_BANK, self::ZENITH_BANK, self::FIRST_BANK];
        // the supported banks for internet banking
        return array_filter(self::BANK_CODES, function ($key) use ($codes) {
            return in_array($key, $codes);
        }, ARRAY_FILTER_USE_KEY);
    }
}
