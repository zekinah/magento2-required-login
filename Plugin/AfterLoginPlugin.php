<?php

namespace Zone\RequiredLogin\Plugin;

use Magento\Customer\Controller\Account\LoginPost;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\Redirect;

class AfterLoginPlugin
{
	const CONFIG_REDIRECT_DASHBOARD = 'customer/startup/redirect_dashboard';

	private $scopeConfig;

    public function __construct(
		ScopeConfigInterface $scopeConfig
    ) {
		$this->scopeConfig = $scopeConfig;
    }

    public function afterExecute(LoginPost $subject, $resultRedirect)
    {
		if($this->scopeConfig->getValue(self::CONFIG_REDIRECT_DASHBOARD)) {
			return $resultRedirect;
		} else {
			$resultRedirect->setPath('/');
			return $resultRedirect;
		}
    }
}