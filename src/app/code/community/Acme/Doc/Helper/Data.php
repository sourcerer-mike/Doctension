<?php

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 08.03.14
 * Time: 22:48
 */
class Acme_Doc_Helper_Data extends Mage_Core_Helper_Abstract
{
    const MODULE_ALIAS = 'acme_doc';
    const MODULE_NAME = 'Acme_Doc';

    /**
     * Get the module name.
     *
     * @param string $appendix
     *
     * @return string
     */
    public static function getModuleName($appendix = '')
    {
        return static::MODULE_NAME . $appendix;
    }

    /**
     * Get an alias for the module.
     *
     * @param string $appendix
     *
     * @return string
     */
    public static function getModuleAlias($appendix = '')
    {
        return static::MODULE_ALIAS . $appendix;
    }
}
