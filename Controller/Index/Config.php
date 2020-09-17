<?php

namespace Zone\RequireLogin\Controller\Index;

class Config extends \Magento\Framework\App\Action\Action
{

	protected $helperData;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Zone\RequireLogin\Helper\Data $helperData

	)
	{
		$this->helperData = $helperData;
		return parent::__construct($context);
	}

	public function execute()
	{
		echo $this->helperData->getGeneralConfig('enable');
		exit();

	}
}