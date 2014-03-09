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

        $this->mainSection($data, $output);
        $this->configSection($data, $output);

        $output->addLine();
        $output->addLine();
    }

    /**
     * @param                                 $data
     * @param Acme_Doc_Model_Output_Interface $output
     */
    private function configSection($data, Acme_Doc_Model_Output_Interface $output)
    {
        $configParser = Mage::getModel('acme_doc/parser_config');
        $configParser->parse($data->getConfig(), $output->getSub());
    }

    /**
     * @param                                 $data
     * @param Acme_Doc_Model_Output_Interface $output
     */
    private function mainSection($data, Acme_Doc_Model_Output_Interface $output)
    {
        $helper  = Mage::helper('acme_doc');
        $version = $data->getVersion();
        if (!$version)
        {
            $version = sprintf('<i>%s</i>', $helper->__('unknown'));
        }

        $output->addItemization(
            array(
                $helper->__('Active: %s', $data->getActive()),
                $helper->__('Version: %s', $version),
                $helper->__('Code Pool: %s', $data->getCodePool()),
                $helper->__('Directory: %s', $data->getDirectory()),
            )
        );
    }

    public function getHelper()
    {
        return Mage::helper('acme_doc');
    }
}
