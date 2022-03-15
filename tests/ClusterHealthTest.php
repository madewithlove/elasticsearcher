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

	protected function setUp(): void
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

	public function testCanCheckIfHealthyWhenStatusIsRed()
	{
		$this->assertFalse($this->isClusterHealthy('red'), 'Expected cluster not to be healthy, but it is red');
	}

	public function testCanCheckIfHealthyWhenStatusIsYellow()
	{
		$this->assertFalse($this->isClusterHealthy('yellow'), 'Expected cluster not to be healthy, but it is yellow');
	}

	public function testCanCheckIfHealthyWhenStatusIsGreen()
	{
		$this->assertTrue($this->isClusterHealthy('green'), 'Expected cluster to be healthy, but it is not green');
	}

	/**
	 * Check if the cluster is healthy, given the status of the cluster.
	 *
	 * @param string $status
	 *
	 * @return bool
	 */
	protected function isClusterHealthy($status = 'green')
	{
		$this->clusterMock->expects($this->any())
			->method('health')
			->willReturn([
				'status' => $status,
			]);

		$this->clientMock->expects($this->any())
			->method('cluster')
			->willReturn($this->clusterMock);

		return $this->getElasticSearcher()->isHealthy();
	}
}
