<?php

/**
 * Looks up the module config and generates output for it.
 */
class Acme_Doc_Model_Parser_Config implements Acme_Doc_Model_Parser_Interface
{

    /**
     * Get the module helper.
     *
     * @return mixed
     */
    public function getHelper()
    {
        return Mage::helper('acme_doc');
    }

    /**
     * Put all possible information to the output.
     *
     * @param Acme_Doc_Model_Reflect_Module_Config $data
     * @param Acme_Doc_Model_Output_Interface      $output
     */
    public function parse($data, Acme_Doc_Model_Output_Interface $output)
    {
        $output->addHeading('Configuration of ' . $data->getModule()->getName());

        $this->blocksSection($data, $output);
        $this->modelsSection($data, $output);

        $this->rewritesSection($data, $output->getSub());
    }

    /**
     * Analyse for rewrites and write information to output.
     *
     * @param                         Acme_Doc_Model_Reflect_Module_Config $data
     * @param Acme_Doc_Model_Output_Interface                              $output
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
     * @param $data
     * @param $scopeOut
     * @param $baseDirectory
     * @param $area
     * @param $classParser
     */
    protected function _aliasParse($data, $scopeOut, $baseDirectory, $area, $classParser)
    {
        foreach ($data->getFiles($area) as $scope => $aliasClasses)
        {
            $scope = ucfirst($scope);

            foreach ($aliasClasses as $alias => $filePath)
            {
                $scopeOut->addHeading($this->getHelper()->__('%s %s with alias "%s"', $scope, $area, $alias));

                $classOut = $scopeOut->getSub();
                foreach ($filePath as $classPath)
                {
                    $className  = str_replace($baseDirectory, '', $classPath);
                    $className  = str_replace('.php', '', $className);
                    $namesSet   = explode('/', $className);
                    $namesSet   = array_map('lcfirst', $namesSet);
                    $modelAlias = $alias . '/' . implode('_', $namesSet);

                    $classParser->parse($modelAlias, $classOut);
                    $classOut->addLine();
                }
            }
        }
    }

    /**
     * Send information about the modules blocks to output.
     *
     * @param Acme_Doc_Model_Reflect_Module_Config $data
     * @param Acme_Doc_Model_Output_Interface      $output
     */
    protected function blocksSection($data, $output)
    {
        $output->addHeading(
            $this->getHelper()->__('Blocks')
        );

        $scopeOut = $output->getSub();
        $this->_aliasParse(
            $data,
            $scopeOut,
            $data->getModule()->getBlockDirectory(),
            'blocks',
            Mage::getModel('acme_doc/parser_blockAlias')
        );
    }

    /**
     * Put information about the possible models in output.
     *
     * @param Acme_Doc_Model_Reflect_Module_Config $data
     * @param Acme_Doc_Model_Output_Interface      $output
     */
    protected function modelsSection($data, $output)
    {
        $output->addHeading(
            $this->getHelper()->__('Models')
        );

        $scopeOut = $output->getSub();
        $this->_aliasParse(
            $data,
            $scopeOut,
            $data->getModule()->getModelDirectory(),
            'models',
            Mage::getModel('acme_doc/parser_modelAlias')
        );
    }
}
