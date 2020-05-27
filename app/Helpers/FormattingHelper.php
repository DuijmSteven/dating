<?php

namespace App\Helpers;

/**
 * Class FormattingHelper
 * @package App\Helpers
 */
class FormattingHelper
{
    /**
     * @param string $string
     * @return string|string[]|null
     */
    public static function stripPhonesAndEmails(string $string) : string
    {
        $stringWithoutPhoneNumbers = preg_replace('/([0-9]+){7,12}/', '(phone hidden)', $string);
        $stringWithoutPhoneNumbersAndEmails = preg_replace('/([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/','(email hidden)', $stringWithoutPhoneNumbers);

        $stringWithoutDomainPhoneNumbersAndEmails = preg_replace('/(?i)altijdsex/', '********', $stringWithoutPhoneNumbersAndEmails);


        return $stringWithoutDomainPhoneNumbersAndEmails;
    }
}
