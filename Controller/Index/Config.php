<?php

namespace Zone\RequireLogin\Controller\Index;

use Magento\Framework\App\Action\Context;
use Zone\RequireLogin\Helper\Data;

class Config extends \Magento\Framework\App\Action\Action
{

	protected $data;

	public function __construct(
		Context $context,
		Data $data

	) {
		$this->data = $data;
		return parent::__construct($context);
	}

	public function execute()
	{
		echo $this->data->getGeneralConfig('enable');
		echo $this->data->getPageExeception('target_url_redirect');
		// echo $this->helperData->getPageExeception('select_whitelist');
		echo $this->data->getNotificationExeception('warning_message');
		exit();

	}
}