<?php

/**
 * Fetches an instance of an model alias and delivers information of it.
 */
class Acme_Doc_Model_Parser_ModelAlias extends Acme_Doc_Model_Parser_SimpleClass
{

    /**
     * Full parsing of a model alias.
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

        $this->mainSection($className, $output);
        parent::descriptionSection($className, $output);
        parent::methodsSection($className, $output);
    }

    /**
     * Main information about the models.
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
