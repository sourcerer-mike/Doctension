<?php

class Acme_Doc_Model_Output_Markdown implements Acme_Doc_Model_Output_Interface
{
    protected $_depth = 1;

    public function setDepth($depth)
    {
        $this->_depth = $depth;
    }

    public function __construct($stream)
    {
        if (!is_resource($stream))
        {
            throw new InvalidArgumentException(
                sprintf('Please provide a stream for writing contents not %s.', gettype($stream))
            );
        }

        $this->_stream = $stream;
    }

    public function addHeading($text)
    {
        $this->addLine();
        $this->addLine(str_repeat('#', $this->getDepth()) . ' ' . trim($text));
        $this->addLine();

        return $this;
    }

    public function addLine($text = '')
    {
        fwrite($this->_stream, $text . "\n");

        return $this;
    }

    public function getDepth()
    {
        return $this->_depth;
    }

    /**
     * @return resource
     */
    public function getStream()
    {
        return $this->_stream;
    }

    public function getSub()
    {
        $sub = clone $this;
        $sub->setDepth($this->getDepth() + 1);

        return $sub;
    }

    /**
     * @param resource $stream
     */
    public function setStream($stream)
    {
        $this->_stream = $stream;
    }

    public function addItemization($array)
    {
        $array = (array) $array;
        foreach ($array as $key => $item)
        {
            if (is_array($item))
            {
                $this->_depth++;
                $this->addItemization($key);
                $this->addItemization($item);
                $this->_depth--;
            } else {
                $this->addLine(' - ' . $item);
            }
        }

        return $this;
    }
}
