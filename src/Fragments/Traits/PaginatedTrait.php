<?php
namespace ElasticSearcher\Fragments\Traits;

/**
 * Shortcut to adding pagination to a Query.
 *
 * @package ElasticSearcher\Fragments\Traits
 */
trait PaginatedTrait
{
	/**
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return static
	 */
	public function paginate($page, $perPage = 30)
	{
		$this->set('from', ($perPage * ($page - 1)));
		$this->set('size', $perPage);

		return $this;
	}
}
