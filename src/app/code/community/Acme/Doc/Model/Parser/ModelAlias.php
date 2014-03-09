<?php

class Acme_Doc_Model_Parser_ModelAlias implements Acme_Doc_Model_Parser_Interface
{

    public function getHelper()
    {
        return Mage::helper('acme_doc');
    }

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

        if (!class_exists($className, false))
        {
            return;
        }

        $this->mainSection($data, $output);

        $this->descriptionSection($data, $output);
    }

    /**
     * @param                                 $data
     * @param Acme_Doc_Model_Output_Interface $output
     */
    private function descriptionSection($data, Acme_Doc_Model_Output_Interface $output)
    {
        $className = Mage::getConfig()->getModelClassName($data);

        if (!$className || !class_exists($className, false))
        {
            return;
        }

        $reflection = new Zend_Reflection_Class($className);
        try
        {
            $shortDescription = $reflection->getDocblock()->getShortDescription();

            if (!$shortDescription)
            {
                return;
            }

            $output->addLine($shortDescription);
        } catch (Exception $e)
        {
            return;
        }
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
