<?php

interface Acme_Doc_Model_Output_Interface
{
    public function __construct($stream);

    public function addItemization($array);

    public function addLine($text = '');

    public function addHeading($text);

    public function getSub();
}
