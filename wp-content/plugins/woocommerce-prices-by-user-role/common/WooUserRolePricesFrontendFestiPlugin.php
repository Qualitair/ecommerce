<?php

class WooUserRolePricesFrontendFestiPlugin extends WooUserRolePricesFestiPlugin
{
    protected $_settings;
    protected $_userRole;
    protected $_textInsteadPrices;
    
    protected function onInit()
    {
        $this->_settings = $this->getOptions('settings');
        
        $this->addActionListener(
            'wp_loaded',
            'onInitFiltersAction',
            10,
            2
        );
    } // end onInit
    
    public function onInitFiltersAction()
    {
        $options = $this->_settings;

        $this->_userRole = $this->getUserRole();
        
        if ($this->_hasHideAddToCartButtonOptionInSettings()) {
            $this->removeAddToCartButtons();
        }
        
        if (!$this->_hasAvailableRoleToViewPrices($options)) {
            $this->addFilterListener(
                'woocommerce_get_price_html',
                'displayPriceContentInAllProductFilter',
                10,
                2
            );
            
            $this->removeAddToCartButtons();
        } else {  
            $this->addFilterListener(
                'woocommerce_get_price_html',
                'displayPriceContentForSingleProductFilter',
                10,
                2
            );

            $this->addFilterListener(
                'woocommerce_loop_add_to_cart_link',
                'removeAddToCartButtonInSingleProductFilter',
                10,
                2
            );
        }

        $this->addFilterListener(
            'woocommerce_get_price',
            'displayPriceFilter',
            10,
            2
        );
        
        $this->addFilterListener(
            'woocommerce_grouped_price_html',
            'displayGroupedProductPriceFilter',
            10,
            2
        );
        $this->addFilterListener(
            'woocommerce_variable_sale_price_html',
            'displayGroupedProductPriceFilter',
            10,
            2
        );
    } // end onInitFiltersAction
    
    public function displayPriceContentForSingleProductFilter($content, $item)
    {
        if(!$this->_hasIdInProductObject($item)) {
            return $content;    
        }
        
        if (!$this->_hasAvailableRoleToViewPricesInEachProduct($item->id)) {
            $this->removeAddToCartButtons(true);
            return $this->fetchContentInsteadOfPrices();
        }
        
        return $content;
    } //end displayPriceContentForSingleProductFilter
    
    private function _hasIdInProductObject($product)
    {
        return isset($product->id);   
    } // end  _hasIdInProductObject
    
    private function _isNotAvailableForDisplayAddToCartButtons()
    {
        return $this->_hasHideAddToCartButtonOptionInSettings()
               || !$this->_hasAvailableRoleToViewPrices();
    } //end _isNotAAvailableForDisplayAddToCartButtons
    
    public function displayPriceContentInAllProductFilter()
    {
        return $this->fetchContentInsteadOfPrices();
    } //end displayPriceContentInAllProductFilter
    
    public function removeAddToCartButtonInSingleProductFilter($button, $item)
    {
        if (!$this->_hasAvailableRoleToViewPricesInEachProduct($item->id)) {
            return '';
        }

        return $button;
    } //end removeAddToCartButtonInSingleProductFilter
    
    private function _hasHideAddToCartButtonOptionInSettings()
    {
        return array_key_exists('hideAddToCartButton', $this->_settings);
    } //end _hasHideAddToCartButtonOptionInSettings
    
    public function removeAddToCartButtons($productPage = false)
    {
        $this->removeActionListener(
            'woocommerce_single_product_summary',
            'woocommerce_template_single_add_to_cart',
            30
        );

        if ($productPage) {
            return false;
        }
        
        $this->removeActionListener(
            'woocommerce_after_shop_loop_item',
            'woocommerce_template_loop_add_to_cart',
            10
        );
    } //end removeAddToCartButtons
    
    
    public function removeActionListener($tag, $function, $priority)
    {
        remove_action($tag, $function, $priority);
    } //end removeActionListener
    
    public function getPluginTemplatePath($fileName)
    {
        return $this->_pluginTemplatePath.'frontend/'.$fileName;
    } // end getPluginTemplatePath

    public function fetchContentInsteadOfPrices()
    {
        $vars = array(
            'text' => $this->_textInsteadPrices
        );
        
        return $this->fetch('custom_text.phtml', $vars);
    } // end fetchContentInsteadOfPrices

    private function _isActiveOnlyRegisteredUsersMode(&$options)
    {
       return array_key_exists('onlyRegisteredUsers', $options);
    } // end _isActiveOnlyRegisteredUsersMode
    
    private function _hasAvailableRoleToViewPrices(&$options)
    {
        if (!$this->_isAvailableForUnregisteredUsers($options)) {
            $this->setValueForContentInsteadOfPrices('textForUnregisterUsers');
            return false;
        }

        if (!$this->_isAvailableForRegisteredUsers($options)) {
            $this->setValueForContentInsteadOfPrices('textForRegisterUsers');
            return false;
        }

        return true;
    } // end _hasAvailableRoleToViewPrices
    
    private function _hasAvailableRoleToViewPricesInEachProduct($productId)
    {
        $options = $this->getMetaOptions(
            $productId,
            'festiUserRoleHidenPrices'
        );
        
        if (!$options) {
            return true;
        }
        
        return $this->_hasAvailableRoleToViewPrices($options);
    } // end _hasAvailableRoleToViewPrices
    
    public function setValueForContentInsteadOfPrices($optionName)
    {
        $this->_textInsteadPrices = $this->_settings[$optionName];
    } // end getContentInsteadOfPrices
    
    private function _isAvailableForUnregisteredUsers(&$options)
    {
        return $this->_isRegisteredUser() || (!$this->_isRegisteredUser()
               && !$this->_isActiveOnlyRegisteredUsersMode($options));
        
    } //end _isAvailableForUnregisteredUsers
    
    private function _isAvailableForRegisteredUsers(&$options)
    {
        return !$this->_isRegisteredUser() || ($this->_isRegisteredUser()
               && !$this->_hasHidePriceOptionForUserRole($options));
        
    } //end _isAvailableForRegisteredUsers
    
    private function _hasHidePriceOptionForUserRole(&$options = array())
    {
        return array_key_exists('hidePriceForUserRoles', $options)
               && array_key_exists(
                    $this->_userRole,
                    $options['hidePriceForUserRoles']
               );
    } //end _hasHidePriceOptionForUserRole

    private function _isRegisteredUser()
    {
        return $this->_userRole;
    } // end _isRegisteredUser
    
    public function getUserRole()
    {
        $userId = get_current_user_id();
        
        if (!$userId) {
            return false;    
        }
        
        $userData = get_userdata($userId);

        $role = implode(', ', $userData->roles);
        return $role;
    } // end getUserRole
    
    public function displayPriceFilter($price, $product)
    {
        $userRole = $this->_userRole;
        
        if (!$userRole) {
            return $price;
        }
        
        if ($this->_hasDiscountOrMarkUpForUserRoleInGeneralOptions($userRole)) {
            $newPrice = $this->getPriceWithDiscountOrMarkUp($price);
            return $newPrice;
        }

        if (!$this->_hasUserRoleInActivePLuginRoles($userRole)) {
            return $price;
        }

        $newPrice = $this->getPrices($product, $userRole);
        
        if ($newPrice) {
            return $newPrice;
        }
        
        return $price;
    } // end displayPriceFilter
    
    public function getPriceWithDiscountOrMarkUp($price)
    {
        $amount = $this->getAmountOfDiscountOrMarkUp();

        if ($this->_isPercentDiscountType()) {
            $amount = $this->getAmountOfDiscountOrMarkUpInPercentage(
                $price,
                $amount
            );
        }
        
        if ($this->_isDiscountTypeEnabled()) {
            $newPrice = ($amount > $price) ? 0 : $price - $amount;
        } else {
            $newPrice = $price + $amount;
        }
                
        return $newPrice;
    } // end getPriceWithDiscountOrMarkUp
    
    private function _isDiscountTypeEnabled()
    {
        return $this->_settings['discountOrMakeUp'] == 'discount';
    } // end _isDiscountTypeEnabled

    public function getAmountOfDiscountOrMarkUpInPercentage($price, $discount)
    {
        $discount = $price / 100 * $discount;
        
        return $discount;
    } // end getAmountOfDiscountOrMarkUpInPercentage
        
    public function getAmountOfDiscountOrMarkUp()
    {
        $options = $this->_settings;
        
        return $options['discountByRoles'][$this->_userRole]['value'];
    } // end getAmountOfDiscountOrMarkUp

    private function _isPercentDiscountType()
    {
        $options = $this->_settings;
        
        return $options['discountByRoles'][$this->_userRole]['type'] == 0;
    } // end _isPercentDiscountType
    
    private function _hasDiscountOrMarkUpForUserRoleInGeneralOptions($role)
    {
        if (!$role) {
            return false;
        }
        
        $options = $this->_settings;

        return array_key_exists('discountByRoles', $options)
               && array_key_exists($role, $options['discountByRoles'])
               && $options['discountByRoles'][$role]['value'] != 0;
    } // end _hasDiscountOrMarkUpForUserRoleInGeneralOptions
    
    public function getPrices($product, $role)
    {
        if ($this->_isSimpleOrVariableProductType($product)) {
            return $this->getRolePrice($product->id, $role);
        } elseif ($product->is_type('variation')) {
            return $this->getRolePrice($product->variation_id, $role);
        }
        
        return false;
    } // end getPrices
    
    public function getRolePrice($id, $role)
    {
        if (!$role) {
            return false;
        } 
        
        $priceList = $this->getMetaOptions($id, 'festiUserRolePrices');
            
        if (!$this->_hasRolePriceInProductOptions($priceList, $role)) {
            return false;
        }

        return $priceList[$role];
    } // end getRolePrice
    
    private function _hasRolePriceInProductOptions($priceList, $role)
    {
        return $priceList && array_key_exists($role, $priceList);
    } // end _hasRolePriceInProductOptions
    
    private function _isSimpleOrVariableProductType($product)
    {
        $types = array(
           'simple',
           'variable'
        );
        
        return $product->is_type($types);
    } // end _isSimpleOrVariableProductType
    
    private function _hasUserRoleInActivePLuginRoles($role)
    {
        $activeRoles = $this->getActiveRoles();

        if (!$activeRoles) {
            return false;
        }

        return array_key_exists($role, $activeRoles);
    } // end _hasUserRoleInActivePLuginRoles
    
    public function displayGroupedProductPriceFilter($price, $product)
    {
        $childPrices = $this->getPricesOfChieldProduct($product);

        if ($childPrices) {
            $minPrice = min($childPrices);
            $maxPrice = max($childPrices);
        } else {
            return apply_filters('woocommerce_get_price_html', $price, $this);
        }

        if ($minPrice == $maxPrice) {
            $price = $this->getPriceTax($product, $minPrice);
        } else {
            $minPrice = $this->getPriceTax($product, $minPrice);
            $maxPrice = $this->getPriceTax($product, $maxPrice);
            $price =  sprintf('%1$s-%2$s', $minPrice, $maxPrice);
        }
        return $price;
    } // end displayGroupedProductPriceFilter
    
    public function getPriceTax($product, $price)
    {
        $taxDisplayMode = get_option('woocommerce_tax_display_shop');
        $methodName = 'get_price_'.$taxDisplayMode.'uding_tax';
        
        $price = $product->$methodName(1, $price);
        $price = wc_price($price);
        
        return $price;
    } // getPriceTax
    
    public function getPricesOfChieldProduct($product)
    {
        $productChildrens = $product->get_children();
        $childPrices = array();
        $userRole = $this->_userRole;
        
        foreach ($productChildrens as $childId) {
            $result = $this->_hasDiscountOrMarkUpForUserRoleInGeneralOptions(
                $userRole
            );
            
            if ($result) {
                $child = $this->getWoocommerceProductInstance($childId);
                $price = $this->getPriceWithDiscountOrMarkUp(
                    $child->regular_price
                );
            } else {
                $price = $this->getRolePrice($childId, $userRole); 
            }

            if ($price) {
                $childPrices[] = $price;
            }
        }
        
        if ($childPrices) {
            $childPrices = array_unique($childPrices);   
        }
        
        return $childPrices;
    } // end getPricesOfChieldProduct

    public function addFilterListener($hook, $method)
    {
        $priority = '';
        $acceptedArgs = '';
        
        $args = func_get_args();
        
        if (isset($args[2])) {
            $priority = $args[2];
        }
        
        if (isset($args[2])) {
            $acceptedArgs = $args[2];
        }
        
        add_filter($hook, array(&$this, $method), $priority, $acceptedArgs);
    } // end addActionListener
    
    public function &getWoocommerceProductInstance($productId)
    {
        $product = new WC_Product($productId);
        return $product;
    } // end getWoocommerceInstance
}