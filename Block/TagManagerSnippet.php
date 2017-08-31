<?php

namespace Augustash\GoogleTagManager\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Cookie\Helper\Cookie as HelperCookie;
use Augustash\GoogleTagManager\Helper\Data as GtmHelperData;


class TagManagerSnippet extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Augustash\GoogleTagManager\Helper\Data
     */
    protected $gtmHelperData;

    /**
     * @var \Magento\Cookie\Helper\Cookie
     */
    protected $helperCookie;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * class constructor
     *
     * @param Context                   $context
     * @param GtmHelperData             $helper
     * @param HelperCookie              $cookie
     * @param StoreManagerInterface     $storeManager
     * @param array                     $data
     */
    public function __construct(
        Context $context,
        GtmHelperData $helper,
        HelperCookie $cookie,
        StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        $this->gtmHelperData    = $helper;
        $this->helperCookie     = $cookie;
        $this->storeManager     = $storeManager;

        parent::__construct($context, $data);
    }

    /**
     * Get the currency used in the current store
     *
     * @return string
     */
    public function getCurrentCurrency()
    {
        return $this->storeManager->getStore()->getCurrentCurrencyCode();
    }

    /**
     * Returns the string value for the Google Tag Manager's Tag ID
     *
     * @param  null|integer  $storeId   # Magento store ID
     * @return string
     */
    public function getTagId($storeId = null)
    {
        return $this->gtmHelperData->getTagId($storeId);
    }

    public function canTrackPage()
    {
        if (!$this->helperCookie->isUserNotAllowSaveCookie() && $this->gtmHelperData->isEnabled()) {
            return true;
        }
        return false;
    }

    public function getPageInfo()
    {
        $page = $this->getRequest()->getFullActionName();
        $data = [];

        $data['ecommerce']['currencyCode'] = $this->getCurrentCurrency();

        switch ($page) {
            case 'catalog_category_view':
                $data['pageCategory'] = 'catalog-listing';
                break;

            case 'catalog_product_view':
                $data['pageCategory'] = 'product-details';
                break;

            case 'customer_product_view':
                $data['pageCategory'] = 'customer-login';
                break;

            default:
                // not enough info to determine...cowardly doing nothing
                break;
        }

        return $data;
    }
}
