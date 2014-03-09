<?php

/**
 * Container for a set of information about all installed magento modules.
 */
class Acme_Doc_Model_Reflect_Module_Collection extends Varien_Data_Collection
{
    const MODULE_CODE_POOL = 'codePool';

    const MODULE_NAME = "name";

    const MODULE_VERSION_CACHED = 'version';

    const MODULE_VERSION_CONFIG = 'configVersion';

    const MODULE_VERSION_DATABASE = 'dbVersion';

    public function loadData()
    {
        $moduleConfig = (array) Mage::getConfig()->getNode('modules')->children();

        $fixedDirectories = array(
            'Model',
            'Block',
            'Helper',
        );

        $dynamicDirectories = array(
            'etc',
            'controllers',
            'sql',
            'locale',
        );

        foreach ($moduleConfig as $moduleName => $data)
        {
            $moduleData = array();

            $moduleData['directory'] = str_replace(
                Mage::getBaseDir('base') . '/',
                '',
                Mage::getModuleDir('', $moduleName) . '/'
            );

            foreach ($fixedDirectories as $dir)
            {
                $moduleData[strtolower($dir) . '_directory'] = str_replace(
                    Mage::getBaseDir('base') . '/',
                    '',
                    $moduleData['directory'] . $dir . '/'
                );
            }

            foreach ($dynamicDirectories as $dir)
            {
                $moduleData[strtolower($dir) . '_directory'] = str_replace(
                    Mage::getBaseDir('base') . '/',
                    '',
                    Mage::getModuleDir($dir, $moduleName) . '/'
                );
            }

            $moduleData = array(
                              'active'          => (string) $data->active,
                              'code_pool'       => (string) $data->codePool,
                              self::MODULE_NAME => $moduleName,
                              'version'         => (string) $data->version,
                          ) + $moduleData;

            $moduleModel = Mage::getModel(Acme_Doc_Model_Reflect_Module::ALIAS);
            $moduleModel->setData($moduleData);
            $this->addItem($moduleModel);
        }

        return $this;
    }
}
