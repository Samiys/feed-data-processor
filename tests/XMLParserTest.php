<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Parser\XMLParser;

class XMLParserTest extends TestCase {
    public function testParseValidXML(): void
    {
        $xmlFile = 'feed.xml';
        $xml = XMLParser::parse($xmlFile);
        $this->assertNotNull($xml);
        $this->assertEquals('340', (string)$xml->item[0]->entity_id);
    }

}
?>
