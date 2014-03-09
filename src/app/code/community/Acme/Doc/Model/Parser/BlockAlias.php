<?php

class Acme_Doc_Model_Parser_BlockAlias extends Acme_Doc_Model_Parser_SimpleClass
{

    /**
     * @param string                          $data
     * @param Acme_Doc_Model_Output_Interface $output
     */
    public function parse($data, Acme_Doc_Model_Output_Interface $output)
    {
        $className = Mage::getConfig()->getModelClassName($data);

        if (!$className)
        {
            return;
        }

        $this->mainSection($className, $output);
        parent::descriptionSection($className, $output);
    }

    /**
     * @param                                 $data
     * @param Acme_Doc_Model_Output_Interface $output
     */
    private function mainSection($data, Acme_Doc_Model_Output_Interface $output)
    {
        $className = Mage::getConfig()->getModelClassName($data);

        if (!$className || !class_exists($className, false))
        {
            return;
        }

        $output->addLine(
            $this->getHelper()->__('The alias **%s** will create a new **%s** object.', $data, $className)
        );
    }
}
