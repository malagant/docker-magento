<?php
class Conlabz_Support_IndexController extends Mage_Core_Controller_Front_Action
{

    const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_RECIPIENT_CC  = 'contacts/email/recipient_email_cc';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'contacts/email/email_template';
    const XML_PATH_ENABLED          = 'contacts/contacts/enabled';
    const XML_PATH_TO_NAME          = 'contacts/email/to_name';

    const DEFAULT_PHONE_TEMPLATE_ID = 42;

    /**
     * @var array
     */
    protected $_requiredFields = array(
        'name',
        'zip',
        'city',
        'country',
        'comment'
    );
    
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getStoreConfigFlag(self::XML_PATH_ENABLED)) {
            $this->norouteAction();
        }
    }
    
    protected function getCountry($countryCode)
    {
        $countries = Mage::getBlockSingleton('skylotec/contact')
            ->getCountries();
        foreach ($countries as $country) {
            if ($country['value'] === $countryCode) {
                return $country['label'];
            }
        }
        return false;
    }

    public function postAction()
    {
        $post = $this->getRequest()->getPost();
        if ($post) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;

                foreach ($this->_requiredFields as $requiredField) {
                    if (!Zend_Validate::is(trim($postObject->getData($requiredField)), 'NotEmpty')) {
                        $error = true;
                        break;
                    }
                }

                $country = Mage::getModel('directory/country')->loadByCode($postObject->getCountry());
                $postObject->setCountry($country->getName());

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }

                if (!Zend_Validate::is(trim($post['text']), 'NotEmpty')) {
                    $mailTemplate = Mage::getModel('core/email_template');
                    /* @var $mailTemplate Mage_Core_Model_Email_Template */
                    $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                        ->setReplyTo($post['email'])
                        ->sendTransactional(
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                            null,
                            array('data' => $postObject)
                        );

                    if (!$mailTemplate->getSentSuccess()) {
                        throw new Exception();
                    }

                    Mage::getSingleton('customer/session')->setData('contact_form_success', true);
                }

                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                $this->_redirect('contacts');

                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
                $this->_redirect('contacts');
                return;
            }
        } else {
            $this->_redirect('contacts');
        }
    }
    
    public function phoneAction()
    {
        $response = array(
            'success' => false
        );
        $required = array(
            'name' => 'Please enter your name / contact person.',
            'street' => 'Please enter your street.',
            'zip' => 'Please enter your postcode.',
            'country' => 'Please enter your country.',
            'phoneNumber' => 'Please enter your phone number.',
            'subject' => 'Please enter a subject.'
        );
        if ($this->getRequest()->isPost()) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $post = $this->getRequest()->getPost();
                foreach ($required as $field => $message) {
                    $value = isset($post[$field]) ? trim($post[$field]) : null;
                    if (!Zend_Validate::is($value, 'NotEmpty')) {
                        throw new Exception(__($message));
                    }
                }
                $postObject = new Varien_Object();
                foreach (array_keys($required) as $field) {
                    $postObject->setDataUsingMethod($field, $this->getRequest()->getPost($field, ''));
                }
                $date = Mage::helper('core')->formatDate(date('Y-m-d H:i:s'), 'medium', true);
                $postObject->setDate($date);


                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate
                    ->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                        self::DEFAULT_PHONE_TEMPLATE_ID,
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                    );
                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception(Mage::helper('core')->__('Unable to send mail.'));
                }
                $translate->setTranslateInline(true);
                $response['success'] = true;
                $response['msg'] = Mage::helper("core")->__("Thanks for your callback request. We will get in touch with you on %s as soon as possible.", $postObject->getPhoneNumber());
            } catch (Exception $e) {
                $translate->setTranslateInline(true);
                $response['errorMsg'][] = $e->getMessage();
            }
            return $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode($response)
            );
        }
    }
}
