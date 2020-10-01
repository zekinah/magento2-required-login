<?php

namespace Zone\RequiredLogin\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\App\Response\Http;
use Magento\Framework\UrlInterface;
use Magento\Framework\Message\ManagerInterface;
use Zone\RequiredLogin\Helper\Data;

class CheckRequiredLogin implements ObserverInterface
{
	protected $messageManager;

	protected $url;

	protected $responseFactory;

	protected $data;
	
	public function __construct(
		Data $data,
		UrlInterface $url,
		ManagerInterface $messageManager,
		ResponseFactory $responseFactory
	) {
		$this->messageManager = $messageManager;
		$this->url = $url;
		$this->responseFactory = $responseFactory;
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
				$this->messageManager->addWarningMessage($message);
				$redirectionUrl = $this->url->getUrl($targetUrl);
        		$this->responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();
        		exit;	
			}
		}
	}
}