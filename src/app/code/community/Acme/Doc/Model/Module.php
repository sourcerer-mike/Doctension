<?php

/**
 * Class Acme_Doc_Model_Module
 *
 * @method getCodePool
 * @method getName
 * @method getDirectory
 * @method getModelDirectory
 * @method getBlockDirectory
 * @method getHelperDirectory
 * @method getControllersDirectory
 * @method getEtcDirectory
 * @method getVersion
 * @method getActive
 */
class Acme_Doc_Model_Module extends Varien_Object
{
    const ALIAS = 'acme_doc/module';

    protected $_config;

    public function getConfig()
    {
        $this->_config = Mage::getModel(Acme_Doc_Model_Module_Config::ALIAS);
    }
}
