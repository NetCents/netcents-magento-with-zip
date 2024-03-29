<?php

namespace NetCents\Merchant\Controller\StatusPage;

class Success extends \Magento\Framework\App\Action\Action {
    protected $_checkoutSession;
    protected $_urlBuilder;
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function execute()
    {

        $this->getResponse()->setRedirect(
            $this->_getUrl('checkout/onepage/success')
        );
    }

    protected function _getUrl($path, $secure = null)
    {
        $store = $this->_storeManager->getStore(null);
        $urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        return $urlInterface->getUrl(
            $path,
            ['_store' => $store, '_secure' => $secure === null ? $store->isCurrentlySecure() : $secure]
        );
    }
}
