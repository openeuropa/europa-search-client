<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\Document;
use PHPUnit\Framework\TestCase;

/**
 * Tests the document model class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Model\Document
 */
class DocumentTest extends TestCase
{

    /**
     * Tests the setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $model = new Document();

        $model->setAccessRestriction(true);
        $model->setApiVersion('2.31');
        $children = [
            new Document(),
            new Document(),
            new Document(),
        ];
        $model->setChildren($children);
        $model->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $model->setContentType('text/plain');
        $model->setDatabase(null);
        $model->setDatabaseLabel(null);
        $model->setGroupById(null);
        $model->setLanguage('EN');
        $metadata = [
            'es_SortDate' => [
                '2019-09-20T14:24:08.203+0200'
            ],
            'esST_FileName' => [
                'file.txt'
            ],
        ];
        $model->setMetadata($metadata);
        $model->setPages(1);
        $model->setReference('e1374b8c-4b9e-4959-a5ba-ccaada8153de');
        $model->setSummary('Lorem ipsum dolor...');
        $model->setTitle('Lorem ipsum');
        $model->setUrl('http://localhost/uuid/e1374b8c-4b9e-4959-a5ba-ccaada8153de');
        $model->setWeight(0);

        $this->assertEquals('2.31', $model->getApiVersion());
        $this->assertEquals($children, $model->getChildren());
        $this->assertEquals('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $model->getContent());
        $this->assertEquals('text/plain', $model->getContentType());
        $this->assertEquals(null, $model->getDatabase());
        $this->assertEquals(null, $model->getDatabaseLabel());
        $this->assertEquals(null, $model->getGroupById());
        $this->assertEquals('EN', $model->getLanguage());
        $this->assertEquals($metadata, $model->getMetadata());
        $this->assertEquals(1, $model->getPages());
        $this->assertEquals('e1374b8c-4b9e-4959-a5ba-ccaada8153de', $model->getReference());
        $this->assertEquals('Lorem ipsum dolor...', $model->getSummary());
        $this->assertEquals('Lorem ipsum', $model->getTitle());
        $this->assertEquals('http://localhost/uuid/e1374b8c-4b9e-4959-a5ba-ccaada8153de', $model->getUrl());
        $this->assertEquals(0, $model->getWeight());
        $this->assertTrue($model->hasAccessRestriction());
    }
}
