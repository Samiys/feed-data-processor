<?php
namespace Tests;

use App\Parser\XMLStreamParser;
use PHPUnit\Framework\TestCase;

class XMLParserTest extends TestCase {
    private $xmlFile;

    protected function setUp(): void {
        $this->xmlFile = __DIR__ . '/../feed.xml';
        if (!file_exists($this->xmlFile)) {
            $this->fail("XML file does not exist at path: " . $this->xmlFile);
        }
    }

    public function testParseValidXML(): void
    {
        $parsedData = [];
        XMLStreamParser::parse($this->xmlFile, function ($node) use (&$parsedData) {
            $parsedData[] = $node;
        });

        $this->assertNotEmpty($parsedData, 'Parsed data is empty');

        if (!empty($parsedData)) {
            $this->assertEquals('340', (string)$parsedData[0]->entity_id);
            $this->assertEquals('41.6000', (string)$parsedData[0]->price);
        }
    }

}
?>
