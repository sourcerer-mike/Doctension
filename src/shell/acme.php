<?php

require 'abstract.php';

class Foo extends Mage_Shell_Abstract
{

    /**
     * Run script
     *
     */
    public function run()
    {
        $collection = Mage::getModel('acme_doc/module_collection')->load();

        $parser = Mage::getModel('acme_doc/parser_module');

        $output = Mage::getModel('acme_doc/output_markdown', STDOUT);

        $output->addHeading('Modules');
        $sub = $output->getSub();

        $parsed = array();
        foreach ($collection as $module)
        {
            /** @var $module Acme_Doc_Model_Module */
            if ($module->getCodePool() == 'core')
            {
                continue;
            }

            if ($module->getName() != 'Acme_Doc')
            {
                continue;
            }

            if (in_array($module->getName(), $parsed))
            { // already parsed: hinder from parsing twice
                continue;
            }

            $parser->parse($module, $sub);
            $parsed[] = $module->getName();
        }
    }
}

(new Foo())->run();
