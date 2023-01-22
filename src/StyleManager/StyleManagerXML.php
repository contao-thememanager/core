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
    public function addGroup(int $id, string $title, string $identifier, string $groupAlias = '', int $sorting = 0): self
    {
        // Flush previous group and its children if it exists
        if (null !== $this->group->id)
        {
            $this->group = new StyleManagerArchiveModel();
        }

        $this->group->id         = $id;
        $this->group->title      = $title;
        $this->group->identifier = $identifier;
        $this->group->groupAlias = $groupAlias;
        $this->group->sorting    = $sorting;

        // Create group array by id
        $this->groups[$id] = [
            'archive' => $this->group,
            'children' => []
        ];

        return $this;
    }

    /**
     * Adds a child to a style manager archive group
     */
    public function addChild(string $title, string $alias, array $cssClasses, array $elements, ?array $options): self
    {
        // Reset previous group child
        if (null !== $this->groupChild->pid)
        {
            $this->groupChild = new StyleManagerModel();
        }

        $this->groupChild->pid        = $this->validateGroup();
        $this->groupChild->title      = $title;
        $this->groupChild->alias      = $alias;
        $this->groupChild->cssClasses = serialize($cssClasses);

        // Add elements
        foreach ($elements as $k => $v)
        {
            if (\is_array($v) && !empty($v))
            {
                $v = serialize($v);
            }

            $this->groupChild->$k = $v;
        }

        // Add options
        foreach ($options as $k => $v)
        {
            $this->groupChild->$k = $v;
        }

        // Push settings into previously created group
        $this->groups[$this->groupChild->pid]['children'][] = $this->groupChild;

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
     * Validates whether the child can be appended to a group
     *
     * @throws LogicException
     */
    private function validateGroup(): int
    {
        if (null === ($id = $this->group->id))
        {
            throw new LogicException('Invalid child position. Did you forget to create a group?');
        }

        return $id;
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
