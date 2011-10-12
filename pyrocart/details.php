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
                    'menu' => TRUE
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
            CREATE TABLE IF NOT EXISTS `pyrocart` (
                `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
                `product_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
                `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                `price` decimal(9,2) DEFAULT NULL,
                `weight` decimal(6,2) DEFAULT NULL,
                `stock` int(11) DEFAULT NULL,
                `currency` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
                `intro` text COLLATE utf8_unicode_ci,
                `description` text COLLATE utf8_unicode_ci,
                `categoryId` int(11) DEFAULT NULL,
                `expire_date` datetime DEFAULT NULL,
                `start_date` datetime DEFAULT NULL,
                `created_on` int(11) DEFAULT NULL,
                `status` int(11) DEFAULT NULL,
                `featured` enum('false','true') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
                `external_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Products table';
        ";

        $pyrocart_categories = "
            CREATE TABLE IF NOT EXISTS `pyrocart_categories` ( 
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `categoryImage` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
                `categoryImageExt` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
                `parentid` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `parentid_fk` (`parentid`),
                CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`parentid`) REFERENCES `product_categories` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Products Categories table';
        ";

        $pyrocart_images = "
            CREATE TABLE IF NOT EXISTS `pyrocart_images` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `product_id` int(11) DEFAULT NULL,
                `productImage` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `productImageThumb` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `design` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `mainImage` int(11) DEFAULT '0',
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ";

        $pyrocart_orders ="
            CREATE TABLE IF NOT EXISTS `pyrocart_orders` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `firstname` varchar(255) DEFAULT NULL,
                `lastname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `email` varchar(255) NOT NULL,
                `telephone` varchar(12) NOT NULL,
                `fax` varchar(12) NOT NULL,
                `company` varchar(255) NOT NULL,
                `address_1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `address_2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `postcode` varchar(5) NOT NULL,
                `country_id` int(11) NOT NULL,
                `zone_id` int(11) NOT NULL,
                `amount` varchar(255) DEFAULT NULL,
                `paymentType` varchar(255) DEFAULT 'sale',
                `tax` float DEFAULT NULL,
                `shiping` float DEFAULT NULL,
                `total` float DEFAULT NULL,
                `payment_method` varchar(255) DEFAULT NULL,
                `auth_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `created_on` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ";

        $pyrocart_order_items ="
            CREATE TABLE IF NOT EXISTS `pyrocart_order_items` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `order_id` int(11) unsigned NOT NULL,
                `product_id` int(11) unsigned DEFAULT NULL,
                `variation_id` int(11) unsigned DEFAULT NULL,
                `price` float DEFAULT NULL,
                `quantity` int(3) DEFAULT NULL,
                `subtotal` float DEFAULT NULL,
                `description` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ";
        
        $pyrocart_countries ="
            CREATE TABLE `pyrocart_countries` (
                `country_id` int(11) NOT NULL AUTO_INCREMENT,
                `country` varchar(255) NOT NULL,
                PRIMARY KEY (`country_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            INSERT INTO `pyrocart_countries` (`country_id`, `country`)
            VALUES
                (1,'Australia'),
                (2,'Canada'),
                (3,'New Zealand'),
                (4,'United Kingdom'),
                (5,'United States');
        ";
        
        $pyrocart_states ="
            CREATE TABLE `pyrocart_states` (
                `zone_id` int(11) NOT NULL AUTO_INCREMENT,
                `country_id` int(11) NOT NULL,
                `state` varchar(255) NOT NULL,
                PRIMARY KEY (`zone_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
            
            INSERT INTO `pyrocart_states` (`zone_id`, `country_id`, `state`)
            VALUES
                (1,1,'Australian Capital Territory'),
                (2,1,'New South Wales'),
                (3,1,'Northern Territory'),
                (4,1,'Queensland'),
                (5,1,'South Australia'),
                (6,1,'Tasmania'),
                (7,1,'Victoria'),
                (8,1,'Western Australia'),
                (9,2,'Alberta '),
                (10,2,'British Columbia '),
                (11,2,'Manitoba '),
                (12,2,'New Brunswick '),
                (13,2,'Newfoundland and Labrador '),
                (14,2,'Northwest Territories '),
                (15,2,'Nova Scotia '),
                (16,2,'Nunavut '),
                (17,2,'Ontario '),
                (18,2,'Prince Edward Island'),
                (19,2,'Quebec '),
                (20,2,'Saskatchewan '),
                (21,2,'Yukon Territory'),
                (22,3,'Auckland '),
                (23,3,'Bay of Plenty '),
                (24,3,'Canterbury '),
                (25,3,'Coromandel '),
                (26,3,'Fiordland '),
                (27,3,'Gisborne '),
                (28,3,'Hawke\'s Bay '),
                (29,3,'Manawatu-Wanganui '),
                (30,3,'Marlborough '),
                (31,3,'Mt Cook-Mackenzie '),
                (32,3,'Nelson'),
                (33,3,'Northland '),
                (34,3,'Otago '),
                (35,3,'Southland '),
                (36,3,'Taranaki '),
                (37,3,'Waikato '),
                (38,3,'Wairprarapa '),
                (39,3,'Wellington '),
                (40,3,'West Coast'),
                (41,4,'Aberdeen'),
                (42,4,'Aberdeenshire '),
                (43,4,'Anglesey '),
                (44,4,'Angus '),
                (45,4,'Argyll and Bute '),
                (46,4,'Bedfordshire '),
                (47,4,'Berkshire '),
                (48,4,'Blaenau Gwent '),
                (49,4,'Bridgend '),
                (50,4,'Bristol '),
                (51,4,'Buckinghamshire '),
                (52,4,'Caerphilly '),
                (53,4,'Cambridgeshire'),
                (54,4,' Cardiff'),
                (55,4,'Carmarthenshire '),
                (56,4,'Ceredigion '),
                (57,4,'Cheshire '),
                (58,4,'Clackmannanshire '),
                (59,4,'Conwy '),
                (60,4,'Cornwall '),
                (61,4,'County Antrim '),
                (62,4,'County Armagh '),
                (63,4,'County Down'),
                (64,4,'County Fermanagh'),
                (65,4,'County Londonderry'),
                (66,4,'County Tyrone '),
                (67,4,'Cumbria'),
                (68,4,'Denbighshire '),
                (69,4,'Derbyshire '),
                (70,4,'Devon '),
                (71,4,'Dorset '),
                (72,4,'Dumfries and Galloway '),
                (73,4,'Dundee '),
                (74,4,'Durham '),
                (75,4,'East Ayrshire '),
                (76,4,'East Dunbartonshire '),
                (77,4,'East Lothian '),
                (78,4,'East Renfrewshire '),
                (79,4,'East Riding of Yorkshire '),
                (80,4,'East Sussex '),
                (81,4,'Edinburgh '),
                (82,4,'Essex '),
                (83,4,'Falkirk '),
                (84,4,'Fife '),
                (85,4,'Flintshire '),
                (86,4,'Glasgow '),
                (87,4,'Gloucestershire '),
                (88,4,'Greater London '),
                (89,4,'Greater Manchester '),
                (90,4,'Gwynedd '),
                (91,4,'Hampshire '),
                (92,4,'Herefordshire '),
                (93,4,'Hertfordshire '),
                (94,4,'Highlands '),
                (95,4,'Inverclyde '),
                (96,4,'Isle of Wight '),
                (97,4,'Kent '),
                (98,4,'Lancashire '),
                (99,4,'Leicestershire '),
                (100,4,'Lincolnshire '),
                (101,4,'Merseyside '),
                (102,4,'Merthyr Tydfil '),
                (103,4,'Midlothian '),
                (104,4,'Monmouthshire '),
                (105,4,'Moray '),
                (106,4,'Neath Port Talbot '),
                (107,4,'Newport '),
                (108,4,'Norfolk '),
                (109,4,'North Ayrshire '),
                (110,4,'North Lanarkshire '),
                (111,4,'North Yorkshire '),
                (112,4,'Northamptonshire '),
                (113,4,'Northumberland '),
                (114,4,'Nottinghamshire '),
                (115,4,'Orkney Islands '),
                (116,4,'Oxfordshire '),
                (117,4,'Pembrokeshire '),
                (118,4,'Perth and Kinross '),
                (119,4,'Powys '),
                (120,4,'Renfrewshire '),
                (121,4,'Rhondda Cynon Taff'),
                (122,4,'Rutland '),
                (123,4,'Scottish Borders '),
                (124,4,'Shetland Islands '),
                (125,4,'Shropshire'),
                (126,4,'Somerset '),
                (127,4,'South Ayrshire '),
                (128,4,'South Lanarkshire '),
                (129,4,'South Yorkshire '),
                (130,4,'Staffordshire '),
                (131,4,'Stirling '),
                (132,4,'Suffolk '),
                (133,4,'Surrey '),
                (134,4,'Swansea '),
                (135,4,'Torfaen '),
                (136,4,'Tyne and Wear '),
                (137,4,'Vale of Glamorgan '),
                (138,4,'Warwickshire '),
                (139,4,'West Dunbartonshire '),
                (140,4,'West Lothian '),
                (141,4,'West Midlands '),
                (142,4,'West Sussex '),
                (143,4,'West Yorkshire '),
                (144,4,'Western Isles '),
                (145,4,'Wiltshire '),
                (146,4,'Worcestershire '),
                (147,4,'Wrexham'),
                (148,5,'Alabama'),
                (149,5,'Alaska'),
                (150,5,'Arizona'),
                (151,5,'Arkansas'),
                (152,5,'California'),
                (153,5,'Colorado'),
                (154,5,'Connecticut'),
                (155,5,'Delaware'),
                (156,5,'Florida'),
                (157,5,'Georgia'),
                (158,5,'Hawaii'),
                (159,5,'Idaho'),
                (160,5,'Illinois'),
                (161,5,'Indiana'),
                (162,5,'Iowa'),
                (163,5,'Kansas'),
                (164,5,'Kentucky'),
                (165,5,'Louisiana'),
                (166,5,'Maine'),
                (167,5,'Maryland'),
                (168,5,'Massachusetts'),
                (169,5,'Michigan'),
                (170,5,'Minnesota'),
                (171,5,'Mississippi'),
                (172,5,'Missouri'),
                (173,5,'Montana'),
                (174,5,'Nebraska'),
                (175,5,'Nevada'),
                (176,5,'New Hampshire'),
                (177,5,'New Jersey'),
                (178,5,'New Mexico'),
                (179,5,'New York'),
                (180,5,'North Carolina'),
                (181,5,'North Dakota'),
                (182,5,'Ohio'),
                (183,5,'Oklahoma'),
                (184,5,'Oregon'),
                (185,5,'Pennsylvania'),
                (186,5,'Rhode Island'),
                (187,5,'South Carolina'),
                (188,5,'South Dakota'),
                (189,5,'Tennessee'),
                (190,5,'Texas'),
                (191,5,'Utah'),
                (192,5,'Vermont'),
                (193,5,'Virginia'),
                (194,5,'Washington'),
                (195,5,'West Virginia'),
                (196,5,'Wisconsin'),
                (197,5,'Wyoming');

        ";
        
        $pyrocart_shipping_fixed ="
            CREATE TABLE `pyrocart_shipping_fixed` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(155) DEFAULT NULL,
                `quantity` int(11) DEFAULT '0',
                `price` decimal(9,2) DEFAULT '0.00',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        
        $pyrocart_shipping_weight ="
            CREATE TABLE `pyrocart_shipping_weight` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `country_id` int(11) NOT NULL,
                `name` varchar(255) NOT NULL,
                `weight_from` decimal(6,2) NOT NULL,
                `weight_to` decimal(6,2) NOT NULL,
                `price` decimal(9,2) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            INSERT INTO `pyrocart_shipping_weight` (`id`, `country_id`, `name`, `weight_from`, `weight_to`, `price`)
            VALUES
                (1,1,'Standard',0.00,0.25,1.60),
                (2,2,'Air Mail',0.00,0.25,10.50),
                (3,2,'Air Mail',0.25,0.50,17.25),
                (4,2,'Air Mail',0.50,0.75,24.00),
                (5,2,'Air Mail',0.75,1.00,30.75);
        ";
        
        //Settings
        $settings_pyrocart_currency = array(
            'slug' => 'pyrocart_currency',
            'title' => 'Currency',
            'description' => 'Select type of currency',
            'default' => 'Dollars',
            'value' => 'Dollars',
            'type' => 'text',
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
            $this->db->insert('settings', $settings_pyrocart_currency) &&
            $this->db->insert('settings', $settings_pyrocart_currency) &&
            $this->db->insert('settings', $settings_pyrocart_currency) &&
            $this->db->insert('settings', $settings_pyrocart_currency) &&

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
            $this->dbforge->drop_table('pyrocart_states')&&
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
            <p>The Pyrocart module is a E-Commerce Module.</p>";
    }
}
/* End of file details.php */
