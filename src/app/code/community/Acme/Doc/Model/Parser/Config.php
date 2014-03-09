<?php

class Acme_Doc_Model_Parser_Config implements Acme_Doc_Model_Parser_Interface
{

    /**
     * @param                         Acme_Doc_Model_Module_Config $data
     * @param Acme_Doc_Model_Output_Interface                      $output
     */
    public function listRewrites($data, Acme_Doc_Model_Output_Interface $output)
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
    public function parse($data, Acme_Doc_Model_Output_Interface $output)
    {
        $output->addHeading('Configuration of ' . $data->getModule()->getName());

        $this->listRewrites($data, $output->getSub());
    }
}
