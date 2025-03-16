<?php

namespace Saldanhakun\Mailtrap\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @var null $storeId
     */
    protected $storeId = null;

    /**
     * @param null $store_id
     * @return bool
     */
    public function isActive($store_id = null)
    {
        if ($store_id == null && $this->getStoreId() > 0) {
            $store_id = $this->getStoreId();
        }

        return $this->scopeConfig->isSetFlag(
            'system/mailtrap/active',
            ScopeInterface::SCOPE_STORE,
            $store_id
        );
    }

    /**
     * Get system config password
     *
     * @param ScopeInterface::SCOPE_STORE $store
     * @return string
     */
    public function getConfigPassword($store_id = null)
    {
        return $this->getConfigValue('password', $store_id);
    }

    /**
     * Get system config username
     *
     * @param ScopeInterface::SCOPE_STORE $store
     * @return string
     */
    public function getConfigUsername($store_id = null)
    {
        return $this->getConfigValue('username', $store_id);
    }

    /**
     * Get system config auth
     *
     * @param ScopeInterface::SCOPE_STORE $store
     * @return string
     */
    public function getConfigAuth($store_id = null)
    {
        return $this->getConfigValue('auth', $store_id);
    }

    /**
     * Get system config host
     *
     * @param ScopeInterface::SCOPE_STORE $store
     * @return string
     */
    public function getConfigSmtpHost($store_id = null)
    {
        return $this->getConfigValue('smtphost', $store_id);
    }

    /**
     * Get system config port
     *
     * @param ScopeInterface::SCOPE_STORE $store
     * @return string
     */
    public function getConfigSmtpPort($store_id = null)
    {
        return $this->getConfigValue('smtpport', $store_id);
    }

    /**
     * Get system config
     *
     * @param String path
     * @param ScopeInterface::SCOPE_STORE $store
     * @return string
     */
    public function getConfigValue($path, $store_id = null)
    {
        //return value from core config
        return $this->getScopeConfigValue(
            "system/mailtrap/{$path}",
            $store_id
        );
    }

    /**
     * @param String path
     * @param ScopeInterface::SCOPE_STORE $store
     * @return mixed
     */
    public function getScopeConfigValue($path, $store_id = null)
    {
        //use global store id
        if ($store_id === null && $this->getStoreId() > 0) {
            $store_id = $this->getStoreId();
        }

        //return value from core config
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $store_id
        );
    }

    /**
     * @return int/null
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * @param int/null $storeId
     */
    public function setStoreId($storeId = null)
    {
        $this->storeId = $storeId;
    }

}
