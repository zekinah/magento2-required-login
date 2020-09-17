<?php
/*
 * Copyright © 2020 Zekinah Lecaros. All rights reserved.
 * zjlecaros@gmail.com
 */
namespace Zone\RequireLogin\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Request\Http;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{

    const XML_PATH_RequireLogin = 'requirelogin/';

    private $customerSession;

    private $adminSession;

    private $httpContext;

    private $request;

    private $store;

    public function __construct(
        Http $request,
        StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Backend\Model\Auth\Session $adminSession,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Http\Context $httpContext
    ) {
        $this->request = $request;
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
        if($this->getGeneralConfig('enable')) {
            return true;
        }
    }

    public function getTargetUrl() {
        $target_url_redirect = $this->getPageExeception('target_url_redirect');
        if($target_url_redirect) {
            return $target_url_redirect;
        }
    }

    public function getWhitelisted() {
        $page_exception = $this->getPageExeception('select_pageexception');
        $default_exception = [
            'adminhtml_auth_login',
            'customer_account_login',
            'customer_account_logoutSuccess',
            'customer_account_create',
            'customer_account_index',
            'customer_account_forgotpassword',
            'customer_account_forgotpasswordpost'
        ];
        $whitelisted = array_merge($page_exception, $default_exception);
        if($whitelisted) {
            return $whitelisted;
        }
    }

    public function getWarningMessage() {
        $warning_message = $this->getNotificationExeception('warning_message');
        if($warning_message) {
            return $warning_message;
        }
    }


    public function checkCustomerlogin() {
        $isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        // $isLoggedIn =  $this->customerSession->isLoggedIn();
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
        $currentUrl = $this->getCurrentUrl();
        $defaultTargetUrl = $this->getTargetUrl();
        if (!in_array($currentAction, $whiteListed) && ($currentUrl != $defaultTargetUrl)) {
            $this->logger->notice('Zone_RequireLogin Blocked :' . $currentAction);
            return false;
        } else {
            return true;
        }
    }
}
