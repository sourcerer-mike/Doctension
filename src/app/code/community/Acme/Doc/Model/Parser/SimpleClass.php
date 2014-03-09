<?php

class Acme_Doc_Model_Parser_SimpleClass implements Acme_Doc_Model_Parser_Interface
{
    public function getHelper()
    {
        return Mage::helper('acme_doc');
    }

    /**
     * @param string                          $className
     * @param Acme_Doc_Model_Output_Interface $output
     */
    public function parse($className, Acme_Doc_Model_Output_Interface $output)
    {
        if (!class_exists($className, false))
        {
            return;
        }

        $this->descriptionSection($className, $output);
    }

    /**
     * @param   string                        $className
     * @param Acme_Doc_Model_Output_Interface $output
     */
    public function descriptionSection($className, Acme_Doc_Model_Output_Interface $output)
    {
        if (!class_exists($className, false))
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
}
