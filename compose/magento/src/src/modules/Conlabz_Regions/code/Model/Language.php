<?php

/**
 * @package
 * @author     David Pommer (conlabz GmbH) <david.pommer@conlabz.de>
 */
class Conlabz_Regions_Model_Language
{
    const XML_PATH_LANGUAGES = 'global/languages';

    // Default Accept-* quality value if none supplied
    const DEFAULT_QUALITY = 1;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var string[]
     */
    protected $defaultCountryCodes = [
        'de' => 'DE',
        'en' => 'GB',
        'es' => 'ES',
        'fr' => 'FR',
        'it' => 'IT'
    ];

    /**
     * @var string
     */
    protected $language;

    /**
     * @var array|float[][]
     */
    private $acceptLanguage;

    /**
     * @var array
     */
    protected $_mapping;

    public function __construct()
    {
        $this->language = strtolower(Mage::helper('regions')->getAcceptLanguage());
    }

    public function setLanguage($language)
    {
        $this->language = strtolower($language);
        return $this;
    }

    public function getLanguageCode()
    {
        if ($this->acceptLanguage === null) {
            $this->acceptLanguage = $this->parseLanguageHeader($this->language);
        }

        return array_key_first($this->acceptLanguage);
    }
    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        if ($this->countryCode === null) {
            $code = array_key_first($this->acceptLanguage[$this->getLanguageCode()]);
            if ($code === '*') {
                $code = $this->defaultCountryCodes[$this->getLanguageCode()] ?? '';
            }
            $this->countryCode = strtoupper($code);
        }
        return $this->countryCode;
    }


    public function getStoreCode(): string
    {
        $mapping = $this->_getMapping();

        $languageCode = $this->getLanguageCode();
        $countryCode = strtoupper($this->getCountryCode());

        if (!isset($mapping[$languageCode])) {
            return $mapping['default'];
        }
        if (!isset($mapping[$languageCode][$countryCode])) {
            return $mapping[$languageCode]['default'];
        }
        return $mapping[$languageCode][$countryCode];
    }

    public function getStoreUrl($store = null): string
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $store ?? $this->getStoreCode();
    }

    protected function _getMapping(): array
    {
        if (null === $this->_mapping) {
            $this->_mapping = Mage::app()
                ->getConfig()
                ->getNode(self::XML_PATH_LANGUAGES)
                ->asArray();
        }
        return $this->_mapping;
    }

    /**
     * Parses the `Accept-Language:` HTTP header and returns an array containing
     * the languages and associated quality.
     *
     * @link       http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
     * @param      string  $language   charset string to parse
     * @return     array
     * @copyright  (c) 2008-2014 Kohana Team
     * @license    http://kohanaframework.org/license
     * @see        https://github.com/kohana/core/blob/3.3/master/classes/Kohana/HTTP/Header.php#L155
     */
    private function parseLanguageHeader($language = null)
    {
        if ($language === null) {
            return array('*' => array('*' => (float) self::DEFAULT_QUALITY));
        }

        $language = $this->acceptQuality(explode(',', (string) $language));

        $parsed_language = array();

        $keys = array_keys($language);
        foreach ($keys as $key) {
            // Extract the parts
            $parts = explode('-', $key, 2);

            // Invalid content type- bail
            if (! isset($parts[1])) {
                $parsed_language[$parts[0]]['*'] = $language[$key];
            } else {
                // Set the parsed output
                $parsed_language[$parts[0]][$parts[1]] = $language[$key];
            }
        }

        return $parsed_language;
    }

    /**
     * Parses an Accept(-*) header and detects the quality
     *
     * @param      array   $parts  accept header parts
     * @return     array
     * @copyright  (c) 2008-2014 Kohana Team
     * @license    http://kohanaframework.org/license
     * @see        https://github.com/kohana/core/blob/3.3/master/classes/Kohana/HTTP/Header.php#L27
     */
    private function acceptQuality(array $parts)
    {
        $parsed = array();

        // Resource light iteration
        $parts_keys = array_keys($parts);
        foreach ($parts_keys as $key) {
            $value = trim(str_replace(array("\r", "\n"), '', $parts[$key]));

            $pattern = '~\b(\;\s*+)?q\s*+=\s*+([.0-9]+)~';

            // If there is no quality directive, return default
            if (! preg_match($pattern, $value, $quality)) {
                $parsed[$value] = (float) self::DEFAULT_QUALITY;
            } else {
                $quality = $quality[2];

                if ($quality[0] === '.') {
                    $quality = '0'.$quality;
                }

                // Remove the quality value from the string and apply quality
                $parsed[trim(preg_replace($pattern, '', $value, 1), '; ')] = (float) $quality;
            }
        }

        return $parsed;
    }
}
