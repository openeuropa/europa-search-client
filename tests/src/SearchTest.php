<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\Search
 */
class SearchTest extends TestCase
{
    use ClientTestTrait;

    /**
     * @covers ::search
     *
     * @dataProvider providerTestSearch
     */
    public function testSearch(string $class, array $arguments): void
    {
        $client = $this->getTestingClient(
            [
                'apiKey' => 'foo',
                'searchApiEndpoint' => 'http://example.com/search',
            ],
            [
                new $class(...$arguments),
            ]
        );

        $result = $client->search('Programme managers');
        $this->assertSame('2.69', $result->getApiVersion());
        $this->assertSame('"Programme managers"', $result->getTerms());
        $this->assertSame(44, $result->getResponseTime());
        $this->assertSame(4, $result->getTotalResults());
        $this->assertSame(1, $result->getPageNumber());
        $this->assertSame(50, $result->getPageSize());
        $this->assertSame('title:ASC', $result->getSort());

        $this->assertSame([], $result->getBestBets());
    }

    public function providerTestSearch(): array
    {
        return [
            [Response::class, [200, [], '{"apiVersion":"2.69","terms":"\"Programme managers\"","responseTime":44,"totalResults":4,"pageNumber":1,"pageSize":50,"sort":"title:ASC","groupByField":null,"queryLanguage":{"language":"en","probability":0.0},"spellingSuggestion":"<b>programmes managed</b>","bestBets":[],"results":[{"apiVersion":"2.69","reference":"31052261Coordinationandsupportaction1418169600000","url":"https://ec.europa.eu/info/funding-tenders/opportunities/data/topicDetails/WATER-5b-2015.json","title":null,"contentType":"text/plain","language":"en","databaseLabel":"SEDIA","database":"SEDIA","summary":null,"weight":9.849739,"groupById":"3","content":"A coordination platform","accessRestriction":false,"pages":null,"metadata":{"keywords":["[\"End-users\",\"Pollution (water, soil), waste disposal and treatm\",\"Water-climate interactions\",\"Water policy\",\"Water supply\",\"Water distribution\",\"Water re-use\",\"Social sciences, interdisciplinary\",\"Wastewater management\",\"Knowledge and Technology transfer\",\"Drinking water treatment\",\"Wastewater treatment\",\"Gender in water resources\",\"Fight against pollution\",\"Environment, Pollution \\u0026 Climate\",\"Water saving\",\"Water harvesting\",\"Integrated management of water\",\"Water resources\",\"Eco-innovation\",\"Water managment\",\"Water research\",\"Climate Change\",\"Programme owners\",\"Ecoinnovation\",\"Climate change\",\"Water scarcity\",\"Water supply\",\"Green economy\",\"Urban\",\"Water quality\",\"Resource efficiency\",\"Green growth\",\"Industrial wastewater management\",\"Water sanitation\",\"Water\",\"Efficiency\",\"Global warming\",\"Programme managers\",\"Water pollution\",\"Water quantity\",\"Water use efficiency\"]"],"sortStatus":["3"],"destination":[],"type":["1"],"title":["A coordination platform"],"focusArea":[],"cftId":["0"],"esST_FileName":["file.txt"],"ccm2Id":["31052261"],"callIdentifier":["H2020-WATER-2014-2015"],"callccm2Id":["31058785"],"frameworkProgramme":["31045243"],"identifier":["WATER-5b-2015"],"es_ContentType":["text/plain"],"programmeDivision":["31047893","31045244","31047972","31047986"],"crossCuttingPriorities":["SSH","Gender","IntlCoop"],"programmePeriod":["2014 - 2020"],"deadlineDate":["2015-04-21T19:00:00.000+0200"],"esDA_IngestDate":["2021-05-11T20:11:42.579+0200"],"typesOfAction":["Coordination and support action"],"esST_URL":["https://ec.europa.eu/info/funding-tenders/opportunities/data/topicDetails/WATER-5b-2015.json"],"esDA_QueueDate":["2021-05-11T20:11:19.633+0200"],"mission":[],"startDate":["2014-12-10T01:00:00.000+0100"],"status":["31094503"],"deadlineModel":["single-stage"]},"children":[]},{"apiVersion":"2.69","reference":"31052257ERA-NETCofund1418169600000","url":"https://ec.europa.eu/info/funding-tenders/opportunities/data/topicDetails/WATER-3-2015.json","title":null,"contentType":"text/plain","language":"en","databaseLabel":"SEDIA","database":"SEDIA","summary":null,"weight":9.549583,"groupById":"3","content":"Stepping up EU research and innovation cooperation in the water area","accessRestriction":false,"pages":null,"metadata":{"keywords":["[\"Water harvesting\",\"Water resources\",\"Agronomy\",\"Agriculture\",\"Integrated management of water\",\"Forestry\",\"Economics and Business\",\"Water policy\",\"Fight against pollution\",\"Soil science\",\"Water supply\",\"Water-climate interactions\",\"Environment, Pollution \\u0026 Climate\",\"Water saving\",\"Wastewater management\",\"Water re-use\",\"Water system modelling\",\"Water technology\",\"Scientific computing, simulation and modelling too\",\"Green growth\",\"SPIRE\",\"Water-related research\",\"Farming\",\"Ecoinnovation\",\"ICT\",\"Forestry\",\"Public-public partnerships\",\"Urban\",\"Brine\",\"Sensors\",\"Process industries\",\"Sludge\",\"Water scarcity\",\"Cities\",\"Wastewater\",\"Transnational projects\",\"Early warning\",\"Resource efficiency\",\"Nexus\",\"Risk assessment\",\"Institutional funding\",\"Programme managers\",\"Water quantity\",\"Expert systems\",\"Re-use\",\"Water\",\"Eco-innovation\",\"Modelling\",\"Green economy\",\"Water quality\",\"JPI on water\",\"Energy\",\"Sewage\",\"Recycling\",\"Programme owners\",\"PPP\",\"Irrigation\"]"],"sortStatus":["3"],"destination":[],"type":["1"],"title":["Stepping up EU research and innovation cooperation in the water area"],"focusArea":[],"cftId":["0"],"esST_FileName":["file.txt"],"ccm2Id":["31052257"],"callIdentifier":["H2020-WATER-2014-2015"],"callccm2Id":["31058785"],"frameworkProgramme":["31045243"],"identifier":["WATER-3-2015"],"es_ContentType":["text/plain"],"programmeDivision":["31047893","31045244","31047972","31047986"],"crossCuttingPriorities":["IntlCoop","cPPP","Y","ERANET"],"programmePeriod":["2014 - 2020"],"deadlineDate":["2015-04-21T19:00:00.000+0200"],"esDA_IngestDate":["2021-05-11T20:11:37.142+0200"],"typesOfAction":["ERA-NET Cofund"],"esST_URL":["https://ec.europa.eu/info/funding-tenders/opportunities/data/topicDetails/WATER-3-2015.json"],"esDA_QueueDate":["2021-05-11T20:11:19.216+0200"],"mission":[],"startDate":["2014-12-10T01:00:00.000+0100"],"status":["31094503"],"deadlineModel":["single-stage"]},"children":[]},{"apiVersion":"2.69","reference":"31052256ERA-NETCofund1386720000000","url":"https://ec.europa.eu/info/funding-tenders/opportunities/data/topicDetails/WATER-3-2014.json","title":null,"contentType":"text/plain","language":"en","databaseLabel":"SEDIA","database":"SEDIA","summary":null,"weight":9.013245,"groupById":"3","content":"Stepping up EU research and innovation cooperation in the water area","accessRestriction":false,"pages":null,"metadata":{"keywords":["[\"Drinking water treatment\",\"Water supply\",\"Urban water management\",\"Automation and control systems\",\"Flood forecasting\",\"Scientific computing, simulation and modelling too\",\"Water distribution\",\"Cost-benefit analysis\",\"Renewable energy sources\",\"Water cycle\",\"Water re-use\",\"Water resources\",\"Wastewater management\",\"Environment, Pollution \\u0026 Climate\",\"Water quality monitoring\",\"Water policy\",\"Integrated management of water\",\"Fight against pollution\",\"Water systems monitoring\",\"Water system modelling\",\"Wastewater treatment\",\"Water technology\",\"Water saving\",\"Water quantity\",\"Recycling\",\"Ecoinnovation\",\"Water\",\"ICT\",\"Green economy\",\"Early warning\",\"Wastewater\",\"Transnational projects\",\"Re-use\",\"PPP\",\"Public-public partnerships\",\"Process industries\",\"SPIRE\",\"Risk assessment\",\"Institutional funding\",\"Eco-innovation\",\"Energy\",\"Resource efficiency\",\"Water scarcity\",\"Sewage\",\"Farming\",\"Irrigation\",\"Sludge\",\"JPI on water\",\"Modelling\",\"Programme managers\",\"Brine\",\"Sensors\",\"Water quality\",\"Nexus\",\"Programme owners\",\"Expert systems\",\"Green growth\",\"Water-related research\",\"Cities\",\"Forestry\",\"Urban\"]"],"sortStatus":["3"],"destination":[],"type":["1"],"title":["Stepping up EU research and innovation cooperation in the water area"],"focusArea":[],"cftId":["0"],"esST_FileName":["file.txt"],"ccm2Id":["31052256"],"callIdentifier":["H2020-WATER-2014-2015"],"callccm2Id":["31058785"],"frameworkProgramme":["31045243"],"identifier":["WATER-3-2014"],"es_ContentType":["text/plain"],"programmeDivision":["31047893","31045244","31047972","31047986"],"crossCuttingPriorities":["Y","cPPP","ERANET"],"programmePeriod":["2014 - 2020"],"deadlineDate":["2014-04-08T19:00:00.000+0200"],"esDA_IngestDate":["2021-05-11T20:13:07.352+0200"],"typesOfAction":["ERA-NET Cofund"],"esST_URL":["https://ec.europa.eu/info/funding-tenders/opportunities/data/topicDetails/WATER-3-2014.json"],"esDA_QueueDate":["2021-05-11T20:11:19.117+0200"],"mission":[],"startDate":["2013-12-11T01:00:00.000+0100"],"status":["31094503"],"deadlineModel":["single-stage"]},"children":[]},{"apiVersion":"2.69","reference":"31052260Coordinationandsupportaction1386720000000","url":"https://ec.europa.eu/info/funding-tenders/opportunities/data/topicDetails/WATER-5a-2014.json","title":null,"contentType":"text/plain","language":"en","databaseLabel":"SEDIA","database":"SEDIA","summary":null,"weight":11.118618,"groupById":"3","content":"Strategic cooperation partnerships","accessRestriction":false,"pages":null,"metadata":{"keywords":["[\"Leadership development\",\"Water technology\",\"Water policy\",\"Water resources\",\"Water supply\",\"Innovation management\",\"Knowledge and Technology transfer\",\"Economics and Business\",\"New business opportunities\",\"Social innovation\",\"Water saving\",\"Ecoinnovation\",\"Water research\",\"Water scarcity\",\"Resource efficiency\",\"Water supply\",\"Water quantity\",\"Urban\",\"Green growth\",\"Water quality\",\"Global warming\",\"Ecosystems\",\"Green economy\",\"Industrial wastewater management\",\"Programme managers\",\"Water use efficiency\",\"Programme owners\",\"Efficiency\",\"Water sanitation\",\"EIP on Water\",\"Climate Change\",\"Water\",\"Water pollution\",\"Eco-innovation\"]"],"sortStatus":["3"],"destination":[],"type":["1"],"title":["Strategic cooperation partnerships"],"focusArea":[],"cftId":["0"],"esST_FileName":["file.txt"],"ccm2Id":["31052260"],"callIdentifier":["H2020-WATER-2014-2015"],"callccm2Id":["31058785"],"frameworkProgramme":["31045243"],"identifier":["WATER-5a-2014"],"es_ContentType":["text/plain"],"programmeDivision":["31047893","31045244","31047972","31047986"],"crossCuttingPriorities":["Gender","IntlCoop"],"programmePeriod":["2014 - 2020"],"deadlineDate":["2014-04-08T19:00:00.000+0200"],"esDA_IngestDate":["2021-05-11T20:13:07.344+0200"],"typesOfAction":["Coordination and support action"],"esST_URL":["https://ec.europa.eu/info/funding-tenders/opportunities/data/topicDetails/WATER-5a-2014.json"],"esDA_QueueDate":["2021-05-11T20:11:19.519+0200"],"mission":[],"startDate":["2013-12-11T01:00:00.000+0100"],"status":["31094503"],"deadlineModel":["single-stage"]},"children":[]}],"warnings":[]}']],
        ];
    }

    /**
     * @covers \OpenEuropa\EuropaSearchClient\Traits\ServicesTrait::getConfiguration
     */
    public function testMissingConfig(): void
    {
        $client = $this->getTestingClient();
        $client->search('abc');
    }
}
