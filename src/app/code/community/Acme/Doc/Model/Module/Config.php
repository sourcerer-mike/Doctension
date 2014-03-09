<?php

/**
 * Class Acme_Doc_Model_Module_Config
 *
 * @method Acme_Doc_Model_Module getModule()
 * @method setModule($module)
 */
class Acme_Doc_Model_Module_Config extends Varien_Object
{
    const ALIAS = 'acme_doc/module_config';

    /** @var Varien_Simplexml_Config */
    protected $_configXml;
    protected $_rewrites;

    public function __construct($module = '')
    {
        parent::__construct();


        if ($module instanceof Acme_Doc_Model_Module)
        {
            $this->setModule($module);
        }
    }

    /**
     * @return Varien_Simplexml_Config
     */
    public function getConfigXml()
    {

        if (!$this->_configXml)
        {
            $configPath = Mage::getBaseDir('base') . '/' . $this->getModule()->getEtcDirectory() . '/config.xml';

            $this->_configXml = new Varien_Simplexml_Config();
            $this->_configXml->loadFile($configPath);
        }

        return $this->_configXml;
    }

    public function getRewrites()
    {
        if (!$this->_rewrites)
        {
            $this->_rewrites = array();
            $rewriteXml      = $this->getConfigXml()->getXpath('//rewrite');

            foreach ($rewriteXml as $node)
            {
                /** @var Varien_Simplexml_Element $node */
                $this->_rewrites[$this->_getXmlPath($node)] = (string) $node->convert;
            }
        }

        return $this->_rewrites;
    }

    /**
     * @param Varien_Simplexml_Element $node
     *
     * @return string
     */
    protected function _getXmlPath($node)
    {
        $xmlPath = '';
        while ($node)
        {
            $xmlPath = $node->getName() . '/' . $xmlPath;
            $node    = $node->getParent();
        }

        return '//' . rtrim($xmlPath, '/');
    }


}
