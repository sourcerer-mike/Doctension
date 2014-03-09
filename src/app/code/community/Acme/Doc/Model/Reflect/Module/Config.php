<?php

/**
 * Class Acme_Doc_Model_Reflect_Module_Config
 *
 * @method Acme_Doc_Model_Reflect_Module getModule()
 * @method setModule($module)
 */
class Acme_Doc_Model_Reflect_Module_Config extends Varien_Object
{
    const ALIAS = 'acme_doc/reflect_module_config';

    /** @var Varien_Simplexml_Config */
    protected $_configXml;
    protected $_rewrites;

    public function __construct($module = '')
    {
        parent::__construct();


        if ($module instanceof Acme_Doc_Model_Reflect_Module)
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

    public function getModelFiles()
    {
        return $this->getFiles('models');
    }

    public function getFiles($area)
    {
        {
            $modelSet = array();
            $modelXml = $this->getConfigXml()->getXpath('//' . $area);

            $modelBaseDir = $this->getModule()->getModelDirectory();
            foreach ($modelXml as $node)
            {
                /** @var Varien_Simplexml_Element $node */
                $scope = $node->getParent()->getName();

                foreach ($node as $alias => $baseClassName)
                {
                    $baseDir = str_replace($this->getModule()->getName() . '_', '', (string) $baseClassName->class);
                    $baseDir = $this->getModule()->getDirectory() . str_replace('_', '/', $baseDir) . '/';

                    $modelSet[$scope][$alias] = array();
                    $regExp = '@' . Mage::getBaseDir() . '/' . preg_quote($baseDir, '@') . '.*\.php@i';
                    foreach ($this->getModule()->getFileList($regExp) as $file)
                    {
                        /** @var SplFileInfo $file */
                        if ($file instanceof SplFileInfo)
                        {
                            $value = $file->getPathname();
                        }
                        elseif (is_array($file))
                        {
                            $value = current($file);
                        }


                        $modelSet[$scope][$alias][] = str_replace(Mage::getBaseDir() . '/', '', $value);
                    }
                }
            }

            return $modelSet;
        }
    }


}
