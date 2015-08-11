<?php

namespace Phoenix\Smartmap\Domain\Model;

class AbstractFilter extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * categories
     *
     * @var array
     */
    protected $categories = NULL;

    /**
     * Returns the categories
     *
     * @return array
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param array $categories
     * @return void
     */
    public function setCategories($categories) {
        $this->categories = $categories;
    }
}
