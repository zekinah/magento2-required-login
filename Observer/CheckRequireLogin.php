<?php
/*
 * Copyright © 2020 Zekinah Lecaros. All rights reserved.
 * zjlecaros@gmail.com
 */
namespace Zone\RequireLogin\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\App\Response\Http;
use Magento\Framework\UrlInterface;
use Magento\Framework\Message\ManagerInterface;
use Zone\RequireLogin\Helper\Data;

class CheckRequireLogin implements ObserverInterface
{
	protected $_messageManager;

	protected $_url;

	protected $_responseFactory;

	protected $data;
	
	public function __construct(
		Data $data,
		UrlInterface $url,
		ManagerInterface $messageManager,
		ResponseFactory $responseFactory
	) {
		$this->_messageManager = $messageManager;
		$this->_url = $url;
		$this->_responseFactory = $responseFactory;
		$this->data = $data;
	}

	public function execute(Observer $observer) {

		$enable = $this->data->getEnable();
		$isActionLogin = $this->data->getProcessAction();
		$isAdminLogin = $this->data->checkAdminlogin();
		$isCustomerLogin = $this->data->checkCustomerlogin();
		$targetUrl = $this->data->getTargetUrl();
		$message = $this->data->getWarningMessage();

		if($isAdminLogin || $isActionLogin){
			return $this;
		}
		
		if(!$isCustomerLogin && $enable) {
			if($isActionLogin) {
    			return $this;
			}else{
				$this->_messageManager->addWarningMessage($message);
				$redirectionUrl = $this->_url->getUrl($targetUrl);
        		$this->_responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();
        		exit;	
			}
		}
	}
}