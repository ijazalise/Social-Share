<?php

/**
 * @copyright Copyright (c) 2018 www.innovadeltech.com
 */

namespace Innovadeltech\Socialshare\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;

class Socialshare extends \Magento\Framework\View\Element\Template {

    public $_coreRegistry;
    public $_scopeConfig;
    public $_product;
    public $_priceHelper;

    public function __construct(
    Context $context, Registry $coreRegistry, PricingHelper $PricingHelper, ScopeConfigInterface $scopeConfig, array $data = array()
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_priceHelper = $PricingHelper;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
        $this->_product = $this->_coreRegistry->registry('current_product');
    }

    public function getShareDescription() {
        $_product = $this->_product;
        $productName = $_product->getName();
        $productPrice = strip_tags($this->_priceHelper->currency(round($_product->getPrice(), 2)));
        $limit = $this->_scopeConfig->getValue('socialshare/whatsapp/description_limit', ScopeInterface::SCOPE_STORE);
        $productShortdesc = substr(strip_tags($_product->getDescription()), 0, $limit);
        $productsData = $this->_scopeConfig->getValue('socialshare/whatsapp/share_desc', ScopeInterface::SCOPE_STORE);
        $productsData = nl2br($productsData);
        $productsData = str_replace(array("[product-title]", "[product-price]", "[product-description]"), array($productName, $productPrice, $productShortdesc), $productsData);
        $productsData = str_replace(array("<br>", "<br/>", "<br />"), array("%0a", "%0a", "%0a"), $productsData);
        return $productsData;
    }

    public function getFbScopInterface() {
        return $facebookID = $this->_scopeConfig->getValue('socialshare/facebook/fb_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $facebookID = ($facebookID != "") ? $facebookID : '1082368948492595';
        $displayLike = $this->_scopeConfig->getValue('socialshare/facebook/display_onlylike', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $like_button = ($displayLike == 1) ? false : true;
        $displayFbCount = $this->_scopeConfig->getValue('socialshare/facebook/display_facebook_count', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $count_button = ($displayFbCount == 1) ? 'button_count' : 'button';
    }

    public function getgplusScopInterface() {
        $count_button = $this->_scopeConfig->getValue('socialshare/googleplus/display_google_count', ScopeInterface::SCOPE_STORE);
        $count_button = ($count_button == 1) ? 'bubble' : 'none';
        return $count_button;
    }

    public function getPinScopInterface() {
        $count_button = $this->_scopeConfig->getValue('socialshare/pinitsharing/display_pinit_count', ScopeInterface::SCOPE_STORE);
        $count_button = ($count_button == 1) ? 'beside' : 'none';
        return $count_button;
    }

}
