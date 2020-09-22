<?php
namespace Zone\RequiredLogin\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Request\Http;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Data extends AbstractHelper
{

    const XML_PATH_RequireLogin = 'requiredlogin/';

    private $customerSession;

    private $adminSession;

    private $logger;

    private $httpContext;

    private $request;

    private $store;

    public function __construct(
        Http $request,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Backend\Model\Auth\Session $adminSession,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Http\Context $httpContext
    ) {
        $this->request = $request;
        $this->logger = $logger;
        $this->customerSession = $customerSession;
        $this->adminSession = $adminSession;
        $this->httpContext = $httpContext;
        $this->store = $storeManager;
        parent::__construct($context);
    }

    public function getConfigValue($field, $storeId = null) {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null) {

        return $this->getConfigValue(self::XML_PATH_RequireLogin . 'general/' . $code, $storeId);
    }

    public function getPageExeception($code, $storeId = null) {

        return $this->getConfigValue(self::XML_PATH_RequireLogin . 'pageexception/' . $code, $storeId);
    }

    public function getNotificationExeception($code, $storeId = null) {

        return $this->getConfigValue(self::XML_PATH_RequireLogin . 'notification/' . $code, $storeId);
    }

    public function getEnable() {
        $enable = $this->getGeneralConfig('enable');
        if($enable) {
            return $enable;
        }
    }

    public function getTargetUrl() {
        $target_url_redirect = $this->getPageExeception('target_url_redirect');
        if($target_url_redirect) {
            return $target_url_redirect;
        }
    }

    public function getWhitelisted() {
        $selected_whitelisted = $this->getPageExeception('select_whitelist');
        if($selected_whitelisted) {
			$selected_whitelisted = explode(",",$selected_whitelisted);
		}
        $default_whitelisted = [
            'adminhtml_auth_login',
            'customer_account_login',
            'customer_account_logoutSuccess',
            'customer_account_create',
            'customer_account_index',
            'customer_account_forgotpassword',
            'customer_account_forgotpasswordpost',
            'customer_account_createpost',
            'customer_account_loginPost',
            'customer_section_load',
            'stripe_payments_admin_configure_webhooks',
            'stripe_payments_webhooks_index',
        ];
        if (is_array($selected_whitelisted) || is_object($selected_whitelisted)) {
            $whitelisted = array_merge($selected_whitelisted, $default_whitelisted);
            foreach($selected_whitelisted as $key => $whitelist){
				if ($whitelist == 'no-route') {
					$selected_whitelisted[$key] = 'cms_noroute_index';
				}
			}
            return $whitelisted;
        }
        return $default_whitelisted;
    }

    public function getWarningMessage() {
        $warning_message = $this->getNotificationExeception('warning_message');
        if($warning_message) {
            return $warning_message;
        }
    }


    public function checkCustomerlogin() {
        // $isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        $isLoggedIn =  $this->customerSession->isLoggedIn();
        if($isLoggedIn) {
            return true;
        }
        return false;
    }

    public function checkAdminlogin() {
        $isLoggedIn = $this->adminSession->isLoggedIn();
        if($isLoggedIn) {
            return true;
        }
        return false;
    }


    public function getProcessAction() {
        $currentAction = $this->request->getFullActionName();
        $whiteListed = $this->getWhitelisted();
        if (in_array($currentAction, $whiteListed)) {
            return true;
        } else {
            $this->logger->notice('Zone_RequiredLogin Blocked :' . $currentAction);
            return false;
        }
    }
}
