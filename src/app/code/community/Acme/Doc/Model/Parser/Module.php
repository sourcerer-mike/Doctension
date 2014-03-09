<?php

class Acme_Doc_Model_Parser_Module implements Acme_Doc_Model_Parser_Interface
{
    const ALIAS = 'acme_doc/parser_module';

    /**
     * @param Acme_Doc_Model_Module           $data
     * @param Acme_Doc_Model_Output_Interface $output
     */
    public function parse($data, Acme_Doc_Model_Output_Interface $output)
    {
        $output->addHeading($data->getName());

        $version = $data->getVersion();
        if (!$version)
        {
            $version = sprintf('<i>%s</i>', Mage::helper('acme_doc')->__('unknown'));
        }

        $output->addItemization(
            array(
                'Active: ' . $data->getActive(),
                'Version: ' . $version,
                'Code Pool: ' . $data->getCodePool(),
                'Directory: ' . $data->getDirectory(),
            )
        );

        // config

        $configParser = Mage::getModel('acme_doc/parser_config');
        $configParser->parse($data->getConfig(), $output->getSub());

        $output->addLine();
        $output->addLine();
    }
}
