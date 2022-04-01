<?php

namespace ContaoThemeManager\Core;

use Contao\File;
use Oveleon\ContaoComponentStyleManager\StyleManagerModel;
use Oveleon\ContaoComponentStyleManager\StyleManagerArchiveModel;

class StyleManagerXMLCreator
{
    /**
     * Creates a style-manager xml file and saves it
     *
     * @param StyleManagerArchiveModel  $objArchive // Style-Manager archive
     * @param StyleManagerModel         $objChild   // Style-Manager child
     * @param string                    $strPath    // Path without suffix
     * @return bool
     * @throws \DOMException
     */
    public static function createFile(StyleManagerArchiveModel $objArchive, StyleManagerModel $objChild, string $strPath): bool
    {
        $strData = self::createStructure($objArchive, $objChild);

        $objFile = new File($strPath.'.xml');
        $blnSuccess = $objFile->write($strData);
        $objFile->close();

        return $blnSuccess;
    }

    /**
     * Creates the xml structure and returns it as a string
     *
     * @param StyleManagerArchiveModel $objArchive
     * @param StyleManagerModel $objChild
     * @return string
     * @throws \DOMException
     */
    public static function createStructure(StyleManagerArchiveModel $objArchive, StyleManagerModel $objChild): string
    {
        // Create xml
        $xml = new \DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;

        $xml->appendChild($archives = $xml->createElement('archives'));
        self::addArchiveData($xml, $archives, $objArchive, $objChild);


        return $xml->saveXML();
    }

    /**
     * Adds an archive data row to the XML document
     *
     * @param \DOMDocument $xml
     * @param \DOMNode $archives
     * @param StyleManagerArchiveModel $objArchive
     * @param StyleManagerModel $objChild
     * @return \DOMDocument
     * @throws \DOMException
     */
    private static function addArchiveData(\DOMDocument $xml, \DOMNode $archives, StyleManagerArchiveModel $objArchive, StyleManagerModel $objChild): \DOMDocument
    {
        // Add archive node
        $row = $xml->createElement('archive');
        $row->setAttribute('identifier', $objArchive->identifier);
        $row = $archives->appendChild($row);

        // Add field data
        self::addRowData($xml, $row, $objArchive->row());

        // Add children data
        self::addChildData($xml, $row, $objChild);

        return $xml;
    }

    /**
     * Adds child data row to the XML document
     *
     * @param \DOMDocument $xml
     * @param \DOMElement $archive
     * @param $objChild
     * @return void
     * @throws \DOMException
     */
    private static function addChildData(\DOMDocument $xml, \DOMElement $archive, $objChild)
    {
        // Add children node
        $children = $xml->createElement('children');
        $children = $archive->appendChild($children);

        $row = $xml->createElement('child');

        $row->setAttribute('alias', $objChild->alias);
        $row = $children->appendChild($row);

        // Add field data
        self::addRowData($xml, $row, $objChild->row());
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
