<?php

namespace Application;

abstract class BasePaginator
{
    /**
     * @var ServiceContainer
     */
    protected $serviceContainer;

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $perPageItem = 10;

    /**
     * @var int
     */
    protected $totalPageCount = 0;

    /**
     * @var int
     */
    protected $totalItemCount = 0;

    /**
     * @var array
     */
    protected $items = array();

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function prepare()
    {
        $request = $this->getServiceContainer()->getRequest();

        $this->setPage($request->get('page'));
        $this->setPerPageItem($request->get('per_page_item'));
    }

    abstract public function loadItems();

    abstract public function calculatePageCount();

    /**
     * @param array $updateParams
     * @return string
     */
    abstract public function createQueryString(array $updateParams = array());

    /**
     * @return \Application\ServiceContainer
     */
    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $page = (int)$page;

        if ($page < 1) {
            $page = 1;
        }

        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $perPageItem
     */
    public function setPerPageItem($perPageItem)
    {
        $perPageItem = (int)$perPageItem;

        if ($perPageItem == 0) {
            $perPageItem = 10;
        }

        if ($perPageItem > 100) {
            $perPageItem = 100;
        }

        if ($perPageItem < 1) {
            $perPageItem = 1;
        }

        $this->perPageItem = $perPageItem;
    }

    /**
     * @return int
     */
    public function getPerPageItem()
    {
        return $this->perPageItem;
    }

    /**
     * @param int $totalItemCount
     */
    public function setTotalItemCount($totalItemCount)
    {
        $this->totalItemCount = $totalItemCount;
    }

    /**
     * @return int
     */
    public function getTotalItemCount()
    {
        return $this->totalItemCount;
    }

    /**
     * @param int $totalPageCount
     */
    public function setTotalPageCount($totalPageCount)
    {
        $this->totalPageCount = $totalPageCount;
    }

    /**
     * @return int
     */
    public function getTotalPageCount()
    {
        return $this->totalPageCount;
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return bool
     */
    public function hasItem()
    {
        return count($this->getItems()) > 0;
    }

    /**
     * @return array
     */
    public function getPages()
    {
        return range(1, $this->getTotalPageCount());
    }

    /**
     * @return bool
     */
    public function hasPage()
    {
        return $this->getTotalPageCount() > 1;
    }

    /**
     * @return bool
     */
    public function hasPrevPage()
    {
        return $this->getPage() > 1;
    }

    /**
     * @return bool
     */
    public function hasNextPage()
    {
        return $this->getTotalPageCount() > 0 && $this->getPage() < $this->getTotalPageCount();
    }

    /**
     * @return int
     */
    public function getNextPage()
    {
        if ($this->hasNextPage()) {
            return $this->getPage() + 1;
        } else {
            return $this->getPage();
        }
    }

    /**
     * @return int
     */
    public function getPrevPage()
    {
        if ($this->hasPrevPage()) {
            return $this->getPage() - 1;
        } else {
            return $this->getPage();
        }
    }
}
