<?php

namespace ContaoThemeManager\Core;

use Contao\File;
use Oveleon\ContaoComponentStyleManager\Model\StyleManagerModel;
use Oveleon\ContaoComponentStyleManager\Model\StyleManagerArchiveModel;

class StyleManagerXMLCreator
{
    /**
     * Creates a StyleManager archive
     *
     * @param int $id
     * @param string $title
     * @param string $identifier
     * @param string|null $groupAlias
     * @param int|null $sorting
     * @return StyleManagerArchiveModel
     */
    public static function createStyleManagerArchive(int $id, string $title, string $identifier, ?string $groupAlias, ?int $sorting): StyleManagerArchiveModel
    {
        $objArchive = new StyleManagerArchiveModel();
        $objArchive->id = $id;
        $objArchive->title = $title;
        $objArchive->identifier = $identifier;
        $objArchive->groupAlias = $groupAlias ?? '';
        $objArchive->sorting = $sorting ?? '0';

        return $objArchive;
    }

    /**
     * Creates a StyleManager child
     *
     * @param int $pid
     * @param string $title
     * @param string $alias
     * @param array $cssClasses
     * @param array $elements
     * @param array|null $options
     * @return StyleManagerModel
     */
    public static function createStyleManagerChild(int $pid, string $title, string $alias, array $cssClasses, array $elements, ?array $options): StyleManagerModel
    {
        $objChild = new StyleManagerModel();
        $objChild->pid = $pid;
        $objChild->title = $title;
        $objChild->alias = $alias;
        $objChild->cssClasses = serialize($cssClasses);

        // Add elements
        foreach($elements as $k => $v)
        {
            if(\is_array($v) && !empty($v))
            {
                $v = serialize($v);
            }

            $objChild->$k = $v;
        }

        // Add options
        foreach($options as $k => $v)
        {
            $objChild->$k = $v;
        }

        return $objChild;
    }

    /**
     * Creates a style-manager xml file and saves it
     *
     * @param StyleManagerArchiveModel  $objArchive // Style-Manager archive
     * @param array                     $arrChildren   // Style-Manager children
     * @param string                    $strPath    // Path without suffix
     * @return bool
     * @throws \DOMException
     */
    public static function createFile(StyleManagerArchiveModel $objArchive, array $arrChildren, string $strPath): bool
    {
        $strData = self::createStructure($objArchive, $arrChildren);

        $objFile = new File($strPath.'.xml');
        $blnSuccess = $objFile->write($strData);
        $objFile->close();

        return $blnSuccess;
    }

    /**
     * Creates the xml structure and returns it as a string
     *
     * @param StyleManagerArchiveModel $objArchive
     * @param array $arrChildren
     * @return string
     * @throws \DOMException
     */
    public static function createStructure(StyleManagerArchiveModel $objArchive, array $arrChildren): string
    {
        // Create xml
        $xml = new \DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;

        $xml->appendChild($archives = $xml->createElement('archives'));
        self::addArchiveData($xml, $archives, $objArchive, $arrChildren);


        return $xml->saveXML();
    }

    /**
     * Adds an archive data row to the XML document
     *
     * @param \DOMDocument $xml
     * @param \DOMNode $archives
     * @param StyleManagerArchiveModel $objArchive
     * @param array $arrChildren
     * @return \DOMDocument
     * @throws \DOMException
     */
    private static function addArchiveData(\DOMDocument $xml, \DOMNode $archives, StyleManagerArchiveModel $objArchive, array $arrChildren): \DOMDocument
    {
        // Add archive node
        $row = $xml->createElement('archive');
        $row->setAttribute('identifier', $objArchive->identifier);
        $row = $archives->appendChild($row);

        // Add field data
        self::addRowData($xml, $row, $objArchive->row());

        // Add children data
        self::addChildrenData($xml, $row, $arrChildren);

        return $xml;
    }

    /**
     * Adds child data row to the XML document
     *
     * @param \DOMDocument $xml
     * @param \DOMElement $archive
     * @param array $arrChildren
     * @return void
     * @throws \DOMException
     */
    private static function addChildrenData(\DOMDocument $xml, \DOMElement $archive, array $arrChildren)
    {
        // Add children node
        $children = $xml->createElement('children');
        $children = $archive->appendChild($children);

        foreach ($arrChildren as $objChild)
        {
            $row = $xml->createElement('child');

            $row->setAttribute('alias', $objChild->alias);
            $row = $children->appendChild($row);

            // Add field data
            self::addRowData($xml, $row, $objChild->row());
        }
    }

    /**
     * Adds an archive data row to the XML document
     *
     * @param \DOMDocument $xml
     * @param \DOMNode $row
     * @param array $arrData
     * @return void
     * @throws \DOMException
     */
    private static function addRowData(\DOMDocument $xml, \DOMNode $row, array $arrData)
    {
        foreach ($arrData as $k=>$v)
        {
            $field = $xml->createElement('field');
            $field->setAttribute('title', $k);
            $field = $row->appendChild($field);

            if ($v === null)
            {
                $v = 'NULL';
            }

            $value = $xml->createTextNode($v);
            $field->appendChild($value);
        }
    }
}
