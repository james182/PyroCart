<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Pyrocart extends Module {

    public $version = '1.0';

    public function info()
    {
            return array(
                    'name' => array(
                            'en' => 'Shopping Cart'
                    ),
                    'description' => array(
                            'en' => 'Sell and manage items online.'				
                    ),
                    'frontend' => TRUE,
                    'backend' => TRUE,
                    'menu' => 'PyroCart'
            );
    }

    public function install()
    {
        $this->dbforge->drop_table('pyrocart');
        $this->dbforge->drop_table('pyrocart_categories');
        $this->dbforge->drop_table('pyrocart_images');
        $this->dbforge->drop_table('pyrocart_orders');
        $this->dbforge->drop_table('pyrocart_order_items');
        $this->dbforge->drop_table('pyrocart_countries');
        $this->dbforge->drop_table('pyrocart_shipping_fixed');
        $this->dbforge->drop_table('pyrocart_shipping_weight');
        $this->dbforge->drop_table('pyrocart_states');

        $pyrocart = "
            CREATE TABLE `".$this->db->dbprefix('pyrocart')."` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `product_code` varchar(255) DEFAULT '',
                `title` varchar(255) NOT NULL DEFAULT '',
                `price` decimal(9,2) DEFAULT NULL,
                `weight` decimal(6,2) DEFAULT NULL,
                `stock` int(11) DEFAULT NULL,
                `currency` varchar(100) DEFAULT '',
                `intro` text,
                `description` text,
                `category_id` int(11) DEFAULT NULL,
                `expire_date` datetime DEFAULT NULL,
                `start_date` datetime DEFAULT NULL,
                `created_on` int(11) DEFAULT NULL,
                `status` int(11) DEFAULT NULL,
                `featured` enum('false','true') NOT NULL DEFAULT 'false',
                `external_url` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";

        $pyrocart_categories = "
            CREATE TABLE `".$this->db->dbprefix('pyrocart_categories')."` ( 
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `category_image` varchar(255) DEFAULT '',
                `category_image_ext` varchar(20) DEFAULT '',
                `parent_id` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";

        $pyrocart_images = "
            CREATE TABLE `".$this->db->dbprefix('pyrocart_images')."` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `product_id` int(11) DEFAULT NULL,
                `product_image` varchar(255) DEFAULT NULL,
                `product_image_thumb` varchar(255) DEFAULT NULL,
                `design` varchar(255) DEFAULT NULL,
                `name` varchar(255) DEFAULT NULL,
                `main_image` int(11) DEFAULT '0',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";

        $pyrocart_orders ="
            CREATE TABLE `".$this->db->dbprefix('pyrocart_orders')."` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `status` varchar(255) DEFAULT NULL,
                `firstname` varchar(255) DEFAULT NULL,
                `lastname` varchar(255) DEFAULT NULL,
                `email` varchar(255) NOT NULL,
                `telephone` varchar(12) NOT NULL,
                `fax` varchar(12) NOT NULL,
                `company` varchar(255) NOT NULL,
                `address_1` varchar(255) DEFAULT NULL,
                `address_2` varchar(255) DEFAULT NULL,
                `city` varchar(255) DEFAULT NULL,
                `postcode` varchar(5) NOT NULL,
                `country_id` int(11) NOT NULL,
                `zone_id` int(11) NOT NULL,
                `amount` varchar(255) DEFAULT NULL,
                `payment_type` varchar(255) DEFAULT 'sale',
                `tax` float DEFAULT NULL,
                `shipping` float DEFAULT NULL,
                `total` float DEFAULT NULL,
                `payment_method` varchar(255) DEFAULT NULL,
                `auth_code` varchar(255) DEFAULT NULL,
                `note` varchar(255) DEFAULT NULL,
                `created_on` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";

        $pyrocart_order_items ="
            CREATE TABLE `".$this->db->dbprefix('pyrocart_order_items')."` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `order_id` int(11) unsigned NOT NULL,
                `product_id` int(11) unsigned DEFAULT NULL,
                `variation_id` int(11) unsigned DEFAULT NULL,
                `price` float DEFAULT NULL,
                `quantity` int(3) DEFAULT NULL,
                `subtotal` float DEFAULT NULL,
                `description` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";
        
        $pyrocart_countries ="
            CREATE TABLE `".$this->db->dbprefix('pyrocart_countries')."` (
                `country_id` int(11) NOT NULL AUTO_INCREMENT,
                `country` varchar(255) NOT NULL,
                PRIMARY KEY (`country_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";
        
        $pyrocart_states ="
            CREATE TABLE `".$this->db->dbprefix('pyrocart_states')."` (
                `zone_id` int(11) NOT NULL AUTO_INCREMENT,
                `country_id` int(11) NOT NULL,
                `state` varchar(255) NOT NULL,
                PRIMARY KEY (`zone_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";
        
        $pyrocart_shipping_fixed ="
            CREATE TABLE `".$this->db->dbprefix('pyrocart_shipping_fixed')."` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(155) DEFAULT NULL,
                `quantity` int(11) DEFAULT '0',
                `price` decimal(9,2) DEFAULT '0.00',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";
        
        $pyrocart_shipping_weight ="
            CREATE TABLE `".$this->db->dbprefix('pyrocart_shipping_weight')."` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `country_id` int(11) NOT NULL,
                `name` varchar(255) NOT NULL,
                `weight_from` decimal(6,2) NOT NULL,
                `weight_to` decimal(6,2) NOT NULL,
                `price` decimal(9,2) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";
        
        //Settings
        $settings_pyrocart_currency = array(
            'slug' => 'pyrocart_currency',
            'title' => 'Currency',
            'description' => 'Select type of currency',
            'default' => 'Dollars',
            'value' => 'Dollars',
            'type' => 'select',
            'options' => 'Dollars=Dollars|Euros=Euros|Pounds=Pounds',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'Pyrocart'
        );
        $settings_pyrocart_featured = array(
            'slug' => 'pyrocart_featured',
            'title' => 'Enable Featured',
            'description' => 'Enable or Disable featured products',
            'default' => '1',
            'value' => '0',
            'type' => 'radio',
            'options' => '0=Disabled|1=Enabled',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'Pyrocart'
        );
        $settings_pyrocart_handling_price = array(
            'slug' => 'pyrocart_handling_price',
            'title' => 'Handling Price $',
            'description' => 'Set the handling price',
            'default' => '0',
            'value' => '2.00',
            'type' => 'text',
            'options' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'Pyrocart'
        );
        $settings_pyrocart_timer = array(
            'slug' => 'pyrocart_timer',
            'title' => 'Product Timer',
            'description' => 'Counts down the time a product is available for',
            'default' => '0',
            'value' => '0',
            'type' => 'radio',
            'options' => '0=Disabled|1=Enabled',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'Pyrocart'
        );
        $settings_pyrocart_weight = array(
            'slug' => 'pyrocart_weight',
            'title' => 'Product Weight',
            'description' => 'The weight of a product - for calculating shipping prices',
            'default' => '1',
            'value' => '0',
            'type' => 'radio',
            'options' => '0=Disabled|1=Enabled',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'Pyrocart'
        );
        

        if($this->db->query($pyrocart)&&
            $this->db->query($pyrocart_categories)&&
            $this->db->query($pyrocart_orders)&&
            $this->db->query($pyrocart_order_items)&&
            $this->db->query($pyrocart_countries)&&
            $this->db->query($pyrocart_shipping_fixed)&&
            $this->db->query($pyrocart_shipping_weight)&&
            $this->db->query($pyrocart_states)&&
            $this->db->query($pyrocart_images)&&

            $this->db->insert('settings', $settings_pyrocart_currency) &&
            $this->db->insert('settings', $settings_pyrocart_featured) &&
            $this->db->insert('settings', $settings_pyrocart_handling_price) &&
            $this->db->insert('settings', $settings_pyrocart_timer) &&
            $this->db->insert('settings', $settings_pyrocart_weight) &&

            (is_dir('uploads/pyrocart') OR mkdir('uploads/pyrocart',0777,TRUE)) )
        {
            if(is_dir('uploads/pyrocart/full') OR mkdir('uploads/pyrocart/full',0777,TRUE)){
                    // created full
            }
            if(is_dir('uploads/pyrocart/thumbs') OR mkdir('uploads/pyrocart/thumbs',0777,TRUE)){
                    // created thumbs
            }
            return TRUE;
        }
    }

    public function uninstall()
    {
        if($this->dbforge->drop_table('pyrocart')&&
            $this->dbforge->drop_table('pyrocart_categories')&&
            $this->dbforge->drop_table('pyrocart_images')&&
            $this->dbforge->drop_table('pyrocart_orders')&&
            $this->dbforge->drop_table('pyrocart_order_items')&&
            $this->dbforge->drop_table('pyrocart_countries')&&
            $this->dbforge->drop_table('pyrocart_shipping_fixed')&&
            $this->dbforge->drop_table('pyrocart_shipping_weight')&&
            $this->dbforge->drop_table('default_pyrocart_states')&&
            $this->db->delete('settings', array('module' => 'Pyrocart')) )
        {
            return TRUE;
        }
    }

    public function upgrade($old_version)
    {
            // Your Upgrade Logic
            return TRUE;
    }

    public function help()
    {
            // Return a string containing help info
            // You could include a file and return it here.
            return "<h4>Overview</h4>
            <p>The PyroCart module is an E-Commerce Module.</p>";
    }
}
/* End of file details.php */
