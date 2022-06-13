<?php

namespace Kriss\Nacos\DTO\Request;

class PageParams
{
    /**
     * 分页条数(默认100条,最大为500)
     * @var int|null
     */
    private ?int $pageSize = null;
    /**
     * 当前页码
     * @var int|null
     */
    private ?int $pageNo = null;

    public function getPageSize(): ?int
    {
        return $this->pageSize;
    }

    public function setPageSize(?int $pageSize): self
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    public function getPageNo(): ?int
    {
        return $this->pageNo;
    }

    public function setPageNo(?int $pageNo): self
    {
        $this->pageNo = $pageNo;
        return $this;
    }
}