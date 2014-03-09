<?php

/**
 * Turns all information in markdown.
 */
class Acme_Doc_Model_Output_Markdown implements Acme_Doc_Model_Output_Interface
{
    protected $_depth = 1;

    /**
     * Set the depth level for the current section.
     *
     * @param $depth
     */
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

    /**
     * Writes the heading of a section to the output.
     *
     * @param $text
     *
     * @return $this
     */
    public function addHeading($text)
    {
        $this->addLine();
        $this->addLine(str_repeat('#', $this->getDepth()) . ' ' . trim($text));
        $this->addLine();

        return $this;
    }

    /**
     * Appends a line to the output.
     *
     * @param string $text
     *
     * @return $this
     */
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
     * Get the current output stream.
     *
     * @return resource
     */
    public function getStream()
    {
        return $this->_stream;
    }

    /**
     * Go one section deeper.
     *
     * @return Acme_Doc_Model_Output_Markdown|static
     */
    public function getSub()
    {
        $sub = clone $this;
        $sub->setDepth($this->getDepth() + 1);

        return $sub;
    }

    /**
     * Set the output stream.
     *
     * @param resource $stream
     */
    public function setStream($stream)
    {
        $this->_stream = $stream;
    }

    /**
     * Add a list to the output.
     *
     * @param $array
     *
     * @return $this
     */
    public function addItemization($array)
    {
        $this->addLine();
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
        $this->addLine();

        return $this;
    }
}
