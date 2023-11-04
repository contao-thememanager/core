<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * @author Sebastian Zoglowek <https://github.com/zoglo>
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\StyleManager;

use Contao\File;
use LogicException;
use Oveleon\ContaoComponentStyleManager\Model\StyleManagerArchiveModel;
use Oveleon\ContaoComponentStyleManager\Model\StyleManagerModel;

/**
 * Creates a xml file that is parsed by the style manager bundle config
 *
 * @author Sebastian Zoglowek <https://github.com/zoglo>
 */
class StyleManagerXML
{
    private \DOMDocument $xml;
    private \DOMNode $archives;
    private StyleManagerArchiveModel $group;
    private StyleManagerModel $groupChild;

    private array $groups = [];

    /**
     * @throws \DOMException
     */
    public function __construct()
    {
        $this->group      = new StyleManagerArchiveModel();
        $this->groupChild = new StyleManagerModel();

        $this->xml = new \DOMDocument('1.0', 'UTF-8');
        $this->xml->formatOutput = true;
        $this->archives = $this->xml->createElement('archives');
        $this->xml->appendChild($this->archives);
    }

    /**
     * Creates a new style-manager-*.xml
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Adds a style manager archive group to the xml
     */
    public function addGroup(string $identifier, int $id = 0, string $title = '', string $groupAlias = '', int $sorting = 0): self
    {
        // If group already exists in custom configuration, use it
        if (isset($this->groups[$identifier]['archive']))
        {
            $this->group = $this->groups[$identifier]['archive'];
            return $this;
        }

        $this->group = new StyleManagerArchiveModel();
        $this->group->identifier = $identifier;

        // Should be set if you create new children but is not mandatory for appending classes
        if (!!$id)
        {
            $this->group->id = $id;
        }

        if (!!$title) {
            $this->group->title = $title;
        }

        if (!!$groupAlias) {
            $this->group->groupAlias = $groupAlias;
        }

        if (!!$sorting) {
            $this->group->sorting = $sorting;
        }

        // Create group array by identifier
        $this->groups[$identifier] = [
            'archive' => $this->group,
            'children' => []
        ];

        return $this;
    }

    /**
     * Adds a child to a style manager archive group
     */
    public function addChild(string $alias, array $cssClasses, string $title = '', array $elements = [], array $options = []): self
    {
        $this->groupChild = new StyleManagerModel();

        if (isset($this->group->id))
        {
            $this->groupChild->pid = $this->group->id;
        }

        if (!!$title)
        {
            $this->groupChild->title = $title;
        }

        $this->groupChild->alias = $alias;
        $this->groupChild->cssClasses = serialize($cssClasses);

        // Add elements
        if (!empty($elements))
        {
            foreach ($elements as $k => $v)
            {
                // Auto-enable parent selectors if specific elements are given
                switch ($k)
                {
                    case 'formFields':
                        $this->groupChild->extendFormFields = 1; break;
                    case 'contentElements':
                        $this->groupChild->extendContentElement = 1; break;
                    case 'modules':
                        $this->groupChild->extendModule = 1; break;
                }

                if (\is_array($v) && !empty($v))
                {
                    $v = serialize($v);
                }

                $this->groupChild->$k = $v;
            }
        }

        // Add options
        if (!empty($options))
        {
            foreach ($options as $k => $v)
            {
                $this->groupChild->$k = $v;
            }
        }

        // Push settings into previously created group
        $this->groups[$this->group->identifier]['children'][] = $this->groupChild;

        return $this;
    }

    /**
     * Saves and creates the style-manager-*.xml file
     *
     * @throws \Exception
     */
    public function save(string $name): bool
    {
        if (empty($this->groups))
        {
            return false;
        }

        foreach ($this->groups as $group)
        {
            $objArchive    = $group['archive'];
            $groupChildren = $group['children'];

            self::addArchiveData($objArchive, $groupChildren);
        }

        // Empty content
        if ((!$file = $this->xml->saveXML()) || !strlen($file))
        {
            return false;
        }

        $objFile = new File('templates/style-manager-' . $name . '.xml');
        $success = $objFile->write($file);
        $objFile->close();

        return $success;
    }

    /**
     * Adds an archive data row to the XML document
     *
     * @throws \DOMException
     */
    private function addArchiveData(StyleManagerArchiveModel $objArchive, array $arrChildren): void
    {
        // Add archive node
        $row = $this->xml->createElement('archive');
        $row->setAttribute('identifier', $objArchive->identifier);
        $row = $this->archives->appendChild($row);

        self::addRowData($row, $objArchive->row());
        self::addChildrenData($row, $arrChildren);
    }

    /**
     * Adds a child data row to the XML document
     *
     * @throws \DOMException
     */
    private function addChildrenData(\DomNode $archive, array $arrChildren): void
    {
        // Add children node
        $children = $this->xml->createElement('children');
        $children = $archive->appendChild($children);

        foreach ($arrChildren as $objChild)
        {
            $row = $this->xml->createElement('child');

            $row->setAttribute('alias', $objChild->alias);
            $row = $children->appendChild($row);

            // Add field data
            self::addRowData($row, $objChild->row());
        }
    }

    /**
     * Adds row data to the XML document
     *
     * @throws \DOMException
     */
    private function addRowData(\DOMNode $row, array $arrData): void
    {
        foreach ($arrData as $k=>$v)
        {
            $field = $this->xml->createElement('field');
            $field->setAttribute('title', $k);
            $field = $row->appendChild($field);

            if ($v === null)
            {
                $v = 'NULL';
            }

            $value = $this->xml->createTextNode($v);
            $field->appendChild($value);
        }
    }
}
