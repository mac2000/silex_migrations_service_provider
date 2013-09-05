<?php
namespace Acme\Migrations;

use Doctrine\DBAL\Migrations\OutputWriter as BaseOutputWriter;
use Symfony\Component\Console\Formatter\OutputFormatter;

class HtmlOutputWriter extends BaseOutputWriter
{
    /**
     * @var OutputFormatter
     */
    private $outputFormatter;
    private $messages = array();

    function __construct(OutputFormatter $outputFormatter)
    {
        $this->outputFormatter = $outputFormatter;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    public function getHtmlFormattedMessages()
    {
        return count($this->messages) > 0 ? '<h3>Migration Log Messages</h3><ol><li>' . implode('</li><li>', $this->getMessages()) . '</li></ol>' : '<h3>Nothing to do</h3>';
    }

    public function write($message)
    {
        $this->messages[] = $this->outputFormatter->format($message);
    }
}