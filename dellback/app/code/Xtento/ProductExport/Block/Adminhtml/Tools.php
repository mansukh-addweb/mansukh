<?php

/**
 * Product:       Xtento_ProductExport
 * ID:            OBHvxiP4q0tZy7NMcEAaUc+iD8GmxkIMVKm4xhYn9DQ=
 * Last Modified: 2016-04-14T15:37:35+00:00
 * File:          app/code/Xtento/ProductExport/Block/Adminhtml/Tools.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Block\Adminhtml;

class Tools extends \Magento\Backend\Block\Template
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('Xtento_ProductExport::tools.phtml');
    }
}
