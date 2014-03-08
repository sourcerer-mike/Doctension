<?php

class Acme_Doc_Model_Module_Collection extends Varien_Data_Collection
{
    const MODULE_CODE_POOL = 'codePool';

    const MODULE_NAME = "name";

    const MODULE_VERSION_CACHED = 'version';

    const MODULE_VERSION_CONFIG = 'configVersion';

    const MODULE_VERSION_DATABASE = 'dbVersion';

    public function loadData()
    {
        $moduleConfig = (array) Mage::getConfig()->getNode('modules')->children();

        /** @var LeMike_DevMode_Helper_Core $helper */
        $helper = Mage::helper('lemike_devmode/core');

        $dir = array(
            'Model',
            'Block',
            'Helper',
            'controllers',
            'etc',
        );

        foreach ($moduleConfig as $moduleName => $data)
        {
            $moduleData = array();

            foreach ($dir as $path)
            {
                $moduleData[strtolower($path) . '_directory'] = str_replace(
                    Mage::getBaseDir('base') . '/',
                    '',
                    Mage::getModuleDir($path, $moduleName)
                );
            }

            $moduleData['directory'] = str_replace(
                Mage::getBaseDir('base') . '/',
                '',
                dirname(Mage::getModuleDir('etc', $moduleName))
            );

            $moduleData = array(
                'active'                => (string) $data->active,
                'code_pool'             => (string) $data->codePool,
                self::MODULE_NAME       => $moduleName,
                'version'               => (string) $data->version,
            ) + $moduleData;

            $moduleModel = Mage::getModel(Acme_Doc_Model_Module::XPATH);
            $moduleModel->setData($moduleData);
            $this->addItem($moduleModel);
        }

        return $this;
    }
}
