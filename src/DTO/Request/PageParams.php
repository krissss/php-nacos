<?php

namespace Kriss\Nacos\DTO\Request;

class PageParams
{
    /**
     * 分页条数(默认100条,最大为500)
     * @var int|null
     */
    private $pageSize;
    /**
     * 当前页码
     * @var int|null
     */
    private $pageNo;

    /**
     * @return int|null
     */
    public function getPageSize(): ?int
    {
        return $this->pageSize;
    }

    /**
     * @param int|null $pageSize
     * @return PageParams
     */
    public function setPageSize(?int $pageSize): PageParams
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPageNo(): ?int
    {
        return $this->pageNo;
    }

    /**
     * @param int|null $pageNo
     * @return PageParams
     */
    public function setPageNo(?int $pageNo): PageParams
    {
        $this->pageNo = $pageNo;
        return $this;
    }
}