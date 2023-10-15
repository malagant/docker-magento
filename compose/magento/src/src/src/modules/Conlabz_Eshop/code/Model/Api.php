<?php

class Conlabz_Eshop_Model_Api
{
    const XML_PATH_SHOP_ID = "eshop/keys/shop_id";
    const XML_PATH_SHOP_SECRET = "eshop/keys/shop_secret";
    const XML_PATH_SHOP_LANGUAGE = "eshop/keys/language";
    const XML_PATH_REDIRECT_URL = 'eshop/keys/redirect_url';

    const WSURL = "https://www.e-shop-direct.com/webservices/";
    const DE_LANGUAGE_ID = 1;
    const EN_LANGUAGE_ID = 2;
    const ES_LANGUAGE_ID = 3;
    const IT_LANGUAGE_ID = 4;
    const FR_LANGUAGE_ID = 5;
    const DEFAULT_LANGUAGE_ID = self::EN_LANGUAGE_ID;

    const DE_COUNTRY_ID = 1;
    const EN_COUNTRY_ID = 2;
    const DEFAULT_COUNTRY_ID = self::EN_COUNTRY_ID;

    const SUCCESS_RESPONSE_CODE = "success";
    const ERROR_RESPONSE_CODE = "failure";

    /**
     *
     * @var string
     */
    private $_channel;

    /**
     *
     * @var array
     */
    private $_channelMapping = array(
        'Outdoor' => array(
            'shop_id' => '50133',
            'secret'  => 'grD54T',
            'channel' => array(
                'id'   => '10',
                'name' => 'Outdoor'
            )
        ),
        'Professional' => array(
            'shop_id' => '50133',
            'secret'  => 'grD54T',
            'channel' => array(
                'id'   => '30',
                'name' => 'Industrie'
            )
        )
    );

    /**
     *
     * @var string
     */
    private $_currency;

    /**
     * Get Shop Id from configuration
     *
     * @return string Shop Id
     */
    public function getShopId()
    {
        return $this->_channelMapping[$this->getChannel()]['shop_id'];
    }

    /**
     * Get Shop Secret from configuration
     *
     * @return string Shop Secret
     */
    public function getShopSecret()
    {
        return $this->_channelMapping[$this->getChannel()]['secret'];
    }

    /**
     * Get Interface Language from configuration. If not defined - default eq 1
     *
     * @return int Language Id
     */
    public function getLanguage()
    {
        $lang = Mage::app()->getLocale()->getLocaleCode();
        if (strpos($lang, "de_") === 0) {
            return self::DE_LANGUAGE_ID;
        }
        /*if (Mage::app()->getLocale()->getLocaleCode() == "it_IT") {
            return self::IT_LANGUAGE_ID;
        }
        if (Mage::app()->getLocale()->getLocaleCode() == "fr_FR") {
            return self::FR_LANGUAGE_ID;
        }*/
        return self::DEFAULT_LANGUAGE_ID;
    }

    /**
     * Get Interface Language from configuration. If not defined - default eq 1
     *
     * @return int Language Id
     */
    public function getCountry()
    {
        $code = Mage::app()->getStore()->getCode();
        if (strpos($code, "uk_") === 0) {
            return self::EN_COUNTRY_ID;
        }
        /*if (strpos($code, "ch_") === 0) {
            return self::DE_COUNTRY_ID;
        }*/
        return '';
    }

    /**
     * Get Interface Language from configuration. If not defined - default eq 1
     *
     * @return int Language Id
     */
    public function getShopCountry()
    {
        $separator = '_';
        $code = Mage::app()->getLocale()->getLocaleCode();
        if (strpos($code, $separator) !== false) {
            list($lang, $country) = explode($separator, $code);
            return $country;
        }
        return '';
    }

    /**
     * Generate and send request to API server
     *
     * @return true | array() - list of errors in exists
     */
    public function sentRequest()
    {
        $validateResult = $this->_validate();
        if ($validateResult === true) {
            $header = $this->buildRequestHeader();
            $body = $this->buildRequestBody();
            return $this->call($header . $body);
        } else {
            return $validateResult;
        }
    }

    /**
     * Generate Request Header Tags
     *
     * @return xml
     */
    public function buildRequestHeader()
    {
        $xml = "<Request>";
        $xml .= "<module>shopbuilder</module>";
        $xml .= "<action>importCart</action>";
        $xml .= "<shopid>" . $this->getShopId() . "</shopid>";
        $xml .= "<secret>" . $this->getShopSecret() . "</secret>";
        $xml .= "</Request>";
        return $xml;
    }

    /**
     * Generate Request Body xml
     *
     * @return xml
     */
    public function buildRequestBody()
    {

        $quote = Mage::getSingleton("checkout/session")->getQuote();
        $cartItems = $quote->getAllVisibleItems();

        $numItems = 0;
        foreach ($cartItems as $item) {
            $numItems += $item->getQty();
        }

        $channelId = $this->getChannelId();
        $channelName = $this->getChannelName();

        $xml = "<RequestData>";
        $xml .= "<eshopdirect>";
        $xml .=" <order>";
        $xml .= "<numitems>{$numItems}</numitems>";
        $xml .= "<languageid>{$this->getLanguage()}</languageid>";
        $country = $this->getCountry();
        if (!empty($country)) {
            $xml .= "<countryid>{$country}</countryid>";
            $xml .= "<webshopcountry>{$this->getShopCountry()}</webshopcountry>";
        }
        $xml .= "<currencyid>{$this->_currency}</currencyid>";
        $xml .= "<ip>{$_SERVER['REMOTE_ADDR']}</ip>";
        $xml .= "<freeshipping>0</freeshipping>";

        $xml .= "<cart>";
        foreach ($cartItems as $item) {
            $xml .= "<item>";

            $imageOriginalUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "catalog/product" . $item->getProduct()->getSmallImage();
            $format = false;
            if ($imageOriginalUrl) {
                $imageUrl = explode(".", $imageOriginalUrl);
                $format = strtolower(end($imageUrl));
            }
            if ($format == 'jpg') {
                $imageUrl = rtrim(Mage::getUrl('e/i/s', array('p' => $item->getProductId() . "." . $format)), "/");
            } else {
                $imageUrl = rtrim(Mage::getUrl('e/i/s', array('p' => $item->getProductId() . ".png")), "/");
            }

            $weight = $item->getWeight();

            if (!$weight) {
                $weight = "0.1";
            }
            $weight = round($weight, 2);

            $xml .= "<code>{$item['sku']}</code>";
            $xml .= "<name>{$item['name']}</name>";
            $xml .= "<quantity>{$item->getQty()}</quantity>";
            $xml .= "<amount>" . round($item['price_incl_tax'], 2) . "</amount>";
            $xml .= "<weight>{$weight}</weight>";
            $xml .= "<picture>{$imageUrl}</picture>";

            $xml .= "<channel>";
            $xml .= "<id>{$channelId}</id>";
            $xml .= "<name>{$channelName}</name>";
            $xml .= "</channel>";

            $xml .= "</item>";
        }

        $xml .= "</cart>";
        $xml .= "</order>";
        $xml .= "</eshopdirect>";
        $xml .= "</RequestData>";

        return $xml;
    }

    /**
     * retrieve channel for current cart
     * if Outdoor AND Professional retrieve Outdoor
     * fallback: Professional
     *
     * @return string
     */
    protected function getChannel()
    {
        if (null === $this->_channel) {
            $quote = Mage::getSingleton("checkout/session")->getQuote();
            $cartItems = $quote->getAllVisibleItems();
            foreach ($cartItems as $item) {
                $product = $item->getProduct();
                $divisions = (array) $product->getAttributeText('divisions');
                if (in_array('Outdoor', $divisions)) {
                    $this->_channel = 'Outdoor';
                    return $this->_channel;
                }
            }
            $this->_channel = 'Professional';
        }
        return $this->_channel;
    }

    protected function getChannelId()
    {
        return $this->_channelMapping[$this->getChannel()]['channel']['id'];
    }

    protected function getChannelName()
    {
        return $this->_channelMapping[$this->getChannel()]['channel']['name'];
    }

    /**
     * Make API call to server
     *
     * @param string XML request data
     * @return server response
     */
    public function call($xml)
    {

        $requestXml = '<?xml version="1.0" encoding="UTF-8" ?>';
        $requestXml .= "<EshopdirectService xml:lang='de-DE'>";
        $requestXml .= $xml;
        $requestXml .= "</EshopdirectService>";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::WSURL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, urlencode($requestXml));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);

        if (!$response) {
            return array('errors' => Mage::helper("eshop")->__("Error appeared during Arendicom connection, please try again later"));
        }

        return $this->checkResponse($response);
    }

    /**
     * Validate API settings before call
     *
     * @return true|array errors
     */
    private function _validate()
    {

        $validated = true;

        $errors = array();
        if (!$this->getShopId()) {
            $errors[] = Mage::helper("eshop")->__("Please enter your ShopID in Arendicom extension configuration");
        }
        if (!$this->getShopSecret()) {
            $errors[] = Mage::helper("eshop")->__("Please enter your Shop Secret in Arendicom extension configuration");
        }

        $this->_currency = Mage::getModel("eshop/currency")->getApiCurrencyId();
        if ($this->_currency === false) {
            $errors[] = Mage::helper("eshop")->__("Current Shop currency is not supported by Arendicom system, please switch to another currency");
        }

        if (sizeof($errors) > 0) {
            $validated = array();
            $validated['errors'] = $errors;
        }

        return $validated;
    }

    /**
     * check and parce XML response from ARendicom
     */
    public function checkResponse($response)
    {

        $responseDoc = new DomDocument();
        $responseDoc->loadXml($response);

        $status = $responseDoc->getElementsByTagName('status')->item(0)->nodeValue;

        $validated = true;
        if ($status == self::ERROR_RESPONSE_CODE) {
            return array("errors" => Mage::helper("eshop")->__("Arendicom error") . ": " . $responseDoc->getElementsByTagName('message')->item(0)->nodeValue);
        }
        if ($status == self::SUCCESS_RESPONSE_CODE) {
            $cartId = $responseDoc->getElementsByTagName('cartid')->item(0)->nodeValue;

            // Generate redirect URL
            $redirectUrl = $this->getRedirectUrl() . "?mid=" . $this->getShopId() . "&act=checkoutonly&langid={$this->getLanguage()}&cartid=" . $cartId;

            if ($country = Mage::getSingleton("checkout/session")->getQuote()->getShippingAddress()->getCountry()) {
                $countryId = Mage::helper("eshop")->getCountryCode($country);
                $redirectUrl .= "&countryid=" . $countryId . "";
            }

            $redirectUrl .= "&altact=buy";
            $redirectUrl .= '&skipfirsthit=true';

            return array("cartid" => $cartId, "redirectUrl" => $redirectUrl);
        }

        $expirationDate = $responseDoc->getElementsByTagName('HardExpirationTime')->item(0)->nodeValue;
    }

    /**
     * @param null|int $store
     * @return mixed
     */
    public function getRedirectUrl($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_REDIRECT_URL, $store);
    }
}
