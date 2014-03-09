<?php

class Acme_Doc_Model_Parser_Module
{
    public function parse(Acme_Doc_Model_Module $module, Acme_Doc_Model_Output_Interface $output)
    {
        $output->addHeading($module->getName());

        $output->addItemization(
            array(
                'Active: ' . $module->getActive(),
                'Version: ' . $module->getVersion(),
                'Code Pool: ' . $module->getCodePool(),
                'Directory: ' . $module->getDirectory(),
            )
        );



        $output->addLine();
        $output->addLine();
    }
}
