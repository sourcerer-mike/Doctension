<?php

class Acme_Doc_Model_Parser_Config implements Acme_Doc_Model_Parser_Interface
{

    public function getHelper()
    {
        return Mage::helper('acme_doc');
    }

    /**
     * @param Acme_Doc_Model_Module_Config    $data
     * @param Acme_Doc_Model_Output_Interface $output
     */
    public function parse($data, Acme_Doc_Model_Output_Interface $output)
    {
        $output->addHeading('Configuration of ' . $data->getModule()->getName());

        $this->modelsSection($data, $output);

        $this->rewritesSection($data, $output->getSub());
    }

    /**
     * @param                         Acme_Doc_Model_Module_Config $data
     * @param Acme_Doc_Model_Output_Interface                      $output
     */
    public function rewritesSection($data, Acme_Doc_Model_Output_Interface $output)
    {
        $rewrites = $data->getRewrites();
        if (count($rewrites))
        {
            $output->addHeading('Rewrites done by ' . $data->getModule()->getName());

            $item = array();
            foreach ($rewrites as $node => $newClass)
            {
                $item[] = str_replace('//config/global/', '', $node) . ' will be ' . $newClass;
            }

            $output->addItemization($item);
            $output->addLine();
        }
    }

    /**
     * @param Acme_Doc_Model_Module_Config    $data
     * @param Acme_Doc_Model_Output_Interface $output
     */
    protected function modelsSection($data, $output)
    {
        $output->addHeading(
            $this->getHelper()->__('Models')
        );

        $sub = $output->getSub();
        foreach ($data->getScopeToClass() as $scope => $aliasClasses)
        {
            $scope = ucfirst($scope);

            foreach ($aliasClasses as $alias => $filePath)
            {
                $sub->addHeading($this->getHelper()->__('%s models with alias "%s"', $scope, $alias));
                $sub->addItemization($filePath);
            }
        }
    }
}
