<?php

/**
 * Class Acme_Doc_Model_Module
 *
 * @method getActive
 * @method getBlockDirectory
 * @method getCodePool
 * @method getControllersDirectory
 * @method getDirectory
 * @method getEtcDirectory
 * @method getHelperDirectory
 * @method getLocaleDirectory
 * @method getModelDirectory
 * @method getName
 * @method getSqlDirectory
 * @method getVersion
 */
class Acme_Doc_Model_Module extends Varien_Object
{
    const ALIAS = 'acme_doc/module';

    protected $_config;

    /**
     * @return Acme_Doc_Model_Module_Config
     */
    public function getConfig()
    {
        if (!$this->_config)
        {
            $this->_config = Mage::getModel(Acme_Doc_Model_Module_Config::ALIAS, $this);
        }

        return $this->_config;
    }
}
