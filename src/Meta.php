<?php

namespace koulab\googledrive;

use Psr\Http\Message\ResponseInterface;

class Meta{

    protected $pages;
    protected $maxPageWidth;

    public function __construct($pages = 1,$maxPageWidth = 60)
    {
        $this->pages = $pages;
        $this->maxPageWidth = $maxPageWidth;
    }

    /**
     * @return int
     */
    public function getMaxPageWidth(): int
    {
        return $this->maxPageWidth;
    }

    /**
     * @return int
     */
    public function getPages(): int
    {
        return $this->pages;
    }

    /**
     * @param int $maxPageWidth
     */
    public function setMaxPageWidth(int $maxPageWidth): void
    {
        $this->maxPageWidth = $maxPageWidth;
    }

    /**
     * @param int $pages
     */
    public function setPages(int $pages): void
    {
        $this->pages = $pages;
    }
}