<?php

/**
 * Gathers information about a class and sends them to the output.
 */
class Acme_Doc_Model_Parser_SimpleClass implements Acme_Doc_Model_Parser_Interface
{
    /**
     * Delivers the short description of the class.
     *
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

    /**
     * Gets the helper of this module.
     *
     * @return mixed
     */
    public function getHelper()
    {
        return Mage::helper('acme_doc');
    }

    /**
     * Sends information about the methods to output.
     *
     * @param                                 $className
     * @param Acme_Doc_Model_Output_Interface $output
     */
    public function methodsSection($className, Acme_Doc_Model_Output_Interface $output)
    {
        if (!class_exists($className, false))
        {
            return;
        }

        $reflection = new Zend_Reflection_Class($className);
        try
        {
            $methods = array();
            foreach ($reflection->getMethods() as $method)
            {
                /** @var Zend_Reflection_Method $method */
                if (!$method->isPublic() ||
                    $method->isConstructor() ||
                    $method->isDestructor() ||
                    $method->isInternal() ||
                    substr($method->getName(), 0, 2) == '__'
                )
                {
                    continue;
                }

                try
                {
                    $shortDescription = $method->getDocblock()->getShortDescription();
                } catch (Exception $e)
                {

                }

                if (!$shortDescription)
                {
                    $shortDescription = $this->getHelper()->__('*Unknown capabilities*');
                }

                $methods[] = sprintf(
                    '**%s**: %s',
                    $method->getName(),
                    $shortDescription
                );
            }

            $output->addItemization($methods);
        } catch (Exception $e)
        {

        }
    }

    /**
     * Sends all possible information to the output.
     *
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
        $this->methodsSection($className, $output);
    }
}
