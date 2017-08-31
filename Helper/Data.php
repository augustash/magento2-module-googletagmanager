<?php

namespace Augustash\GoogleTagManager\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ENABLED  = 'augustash_googletagmanager/general/enabled';
    const XML_PATH_TAG_ID   = 'augustash_googletagmanager/general/tag_id';

    /**
     * Returns a boolean value if this module is enabled/disabled
     * within the Stores > Configuration
     *
     * @param  null|integer  $storeId   # Magento store ID
     * @return boolean
     */
    public function isEnabled($storeId = null)
    {
        return (bool)$this->getConfig(self::XML_PATH_ENABLED, $storeId);
    }

    /**
     * Returns the string value for the Google Tag Manager's Tag ID
     *
     * @param  null|integer  $storeId   # Magento store ID
     * @return string
     */
    public function getTagId($storeId = null)
    {
        if ($this->isEnabled()) {
            return $this->getConfig(self::XML_PATH_TAG_ID, $storeId);
        }

        return '';
    }

    /**
     * Utility function to ease fetching of values from the Stores > Configuration
     *
     * @param  string           $field      # see the etc/adminhtml/system.xml for field names
     * @param  null|integer     $storeId    # Magento store ID
     * @return mixed
     */
    protected function getConfig($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
