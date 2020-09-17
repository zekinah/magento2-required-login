<?php
/*
 * Copyright © 2020 Zekinah Lecaros. All rights reserved.
 * zjlecaros@gmail.com
 */

namespace Zone\RequireLogin\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\App\Response\Http;
use Magento\Framework\UrlInterface;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;
use Zone\RequireLogin\Helper\Data;


class RequireLogin implements ObserverInterface
{

	private $url;

	private $data;

	private $logger;

	private $redirect;

	private $responseFactory;

	private $messageManager;

	public function __construct(
		Data $data,
		ManagerInterface $messageManager,
		UrlInterface $url,
		ResponseFactory $responseFactory,
      	LoggerInterface $logger
	) {
		$this->url = $url;
		$this->data = $data;
		$this->logger = $logger;
		$this->redirect = $redirect;
		$this->messageManager = $messageManager;
		$this->responseFactory = $responseFactory;	
	}

	public function execute(Observer $observer)
	{
		// $actionName = $observer->getEvent()->getRequest()->getFullActionName();
		$enable = $this->data->getEnable();
		$isAdminLogin = $this->data->checkAdminlogin();
		$isCustomerLogin = $this->data->checkCustomerlogin();
		$isActionLogin = $this->data->getProcessAction();
		$message = $this->data->getWarningMessage();
		$targetUrl = $this->data->getTargetUrl();


		/** For Backend Access */
		if($defaultAction && $isAdminLogin){
			return $this;
		}

		/** For Frontend Access Actions*/
		if($isActionLogin && $isCustomerLogin && $enable) {
			return $this;
		} else {
			$this->messageManager->addWarningMessage($message);
			$this->responseFactory->create()->setRedirect($targetUrl)->sendResponse();
			exit;	
		}
		return $this;
	}
}