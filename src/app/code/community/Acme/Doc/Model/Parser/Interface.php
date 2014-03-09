<?php

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 09.03.14
 * Time: 12:43
 */
interface Acme_Doc_Model_Parser_Interface
{
    public function parse($data, Acme_Doc_Model_Output_Interface $output);
}
