<?php

use Elasticsearch\Client;
use Elasticsearch\Namespaces\ClusterNamespace;

class ClusterHealthTest extends ElasticSearcherTestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $clientMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|ClusterNamespace
     */
    private $clusterMock;

    public function setUp()
    {
        parent::setUp();

        $this->clusterMock = $this->getMockBuilder(ClusterNamespace::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->getElasticSearcher()->setClient($this->clientMock);
    }
    public function testCanCheckIfSingleNodeClusterIsHealthy()
    {
        $this->clusterMock->expects($this->once())
            ->method('health')
            ->willReturn([
                'status' => 'yellow',
                'number_of_nodes' => 1,
            ]);

        $this->clientMock->expects($this->once())
            ->method('cluster')
            ->willReturn($this->clusterMock);

        $this->assertTrue($this->getElasticSearcher()->isHealthy(), 'Expected cluster to be healthy, but it is not');
    }

    public function testCanCheckIfSingleNodeClusterIsNoHealthy()
    {
        $this->clusterMock->expects($this->once())
            ->method('health')
            ->willReturn([
                'status' => 'red',
                'number_of_nodes' => 1,
            ]);

        $this->clientMock->expects($this->once())
            ->method('cluster')
            ->willReturn($this->clusterMock);

        $this->assertFalse($this->getElasticSearcher()->isHealthy(), 'Expected cluster to be healthy, but it is not');
    }

    public function testCanCheckIfMultiNodeClusterIsHealthy()
    {
        $this->clusterMock->expects($this->once())
            ->method('health')
            ->willReturn([
                'status' => 'green',
                'number_of_nodes' => 2,
            ]);

        $this->clientMock->expects($this->once())
            ->method('cluster')
            ->willReturn($this->clusterMock);

        $this->assertTrue($this->getElasticSearcher()->isHealthy(), 'Could not detect if cluster is healthy');
    }

    public function testCanCheckIfMultiNodeClusterIsNotHealthy()
    {
        $this->clusterMock->expects($this->at(0))
            ->method('health')
            ->willReturn([
                'status' => 'red',
                'number_of_nodes' => 2,
            ]);

        $this->clusterMock->expects($this->at(1))
            ->method('health')
            ->willReturn([
                'status' => 'yellow',
                'number_of_nodes' => 2,
            ]);

        $this->clientMock->expects($this->any())
            ->method('cluster')
            ->willReturn($this->clusterMock);

        $this->assertFalse($this->getElasticSearcher()->isHealthy(), 'Expected cluster not to be healthy, but it is');
        $this->assertFalse($this->getElasticSearcher()->isHealthy(), 'Expected cluster not to be healthy, but it is');
    }
}