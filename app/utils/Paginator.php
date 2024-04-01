<?php

namespace App\utils;

class Paginator
{
    private int $totalPages, $recordOffset;
    private int $recordsPerPage, $totalRecords, $currentPage = 1;

    public function __construct(int $recordsPerPage, int $totalRecords, int $currentPage)
    {
        $this->recordsPerPage = $recordsPerPage;
        $this->totalRecords = $totalRecords;
        $this->currentPage = $currentPage;

        if ($currentPage < 1) {
            $this->currentPage = 1;
        }

        $this->totalPages = ceil($totalRecords / $recordsPerPage);
        $this->recordOffset = ($this->currentPage - 1) * $this->recordsPerPage;
    }

    # get previous page
    public function getPrevPage(): int|bool
    {
        return ($this->currentPage > 1) ? $this->currentPage - 1 : false;
    }

    # get next page
    public function getNextPage(): int|bool
    {
        return ($this->currentPage < $this->totalPages) ? $this->currentPage + 1 : false;
    }

    # get current page
    public function getCurrPage(): int
    {
        return $this->currentPage;
    }

    # get pages depend on needs
    public function getPages(int $length = 3): array
    {
        $halfLength = floor($length / 2);
        $pageStart = $this->currentPage - $halfLength;
        $pageEnd = $this->currentPage + $halfLength;

        if ($pageStart < 1) {
            $pageStart = 1;
            $pageEnd = $length;
        }

        if ($pageEnd > $this->totalPages) {
            $pageEnd = $this->totalPages;

            $pageStart = $pageEnd - $length + 1;
            if ($pageStart < 1) {
                $pageStart = 1;
            }
        }

        $pages = [];
        for ($i = (int)$pageStart; $i <= $pageEnd; ++$i) {
            array_push($pages, $i);
        }

        return $pages;
    }

    # get records per page
    public function getRecordsPerPage(): int
    {
        return $this->recordsPerPage;
    }

    # get records record offset
    public function getRecordOffset(): int
    {
        return $this->recordOffset;
    }

    # get total pages
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    # get total records
    public function getTotalRecords(): int
    {
        return $this->totalRecords;
    }
}
