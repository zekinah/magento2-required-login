<?php

namespace Zone\RequiredLogin\Controller\Index;

use Magento\Framework\App\Action\Context;
use Zone\RequiredLogin\Helper\Data;

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
		echo $this->data->getGeneralConfig('enable') . "<br>";
		echo $this->data->getPageExeception('target_url_redirect') . "<br>";
		echo $this->data->getPageExeception('select_whitelist') . "<br>";
		echo $this->data->getNotificationExeception('warning_message') . "<br>";
		exit();

	}
}