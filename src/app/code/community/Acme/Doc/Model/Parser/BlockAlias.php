<?php

/**
 * Fetches information about a block and put them to the output.
 */
class Acme_Doc_Model_Parser_BlockAlias extends Acme_Doc_Model_Parser_SimpleClass
{

    /**
     * Send all information of a block to the output.
     *
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

        $this->mainSection($data, $output);
        parent::descriptionSection($className, $output);
    }

    /**
     * Main information of a block.
     *
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
