<?php

declare(strict_types=1);

namespace CyberSpectrum\I18N\Xliff;

use CyberSpectrum\I18N\TranslationValue\TranslationValueInterface;
use CyberSpectrum\I18N\Xliff\Xml\XliffFile;
use CyberSpectrum\I18N\Xliff\Xml\XmlElement;

/**
 * This provides access to a xliff translation value.
 */
class XliffTranslationValue implements TranslationValueInterface
{
    /** The dictionary. */
    protected XliffDictionary $dictionary;

    /** The XML element of this translation value. */
    protected XmlElement $node;

    /**
     * @param XliffDictionary $dictionary The dictionary.
     * @param XmlElement      $node       The XML node.
     */
    public function __construct(XliffDictionary $dictionary, XmlElement $node)
    {
        $this->dictionary = $dictionary;
        $this->node       = $node;
    }

    #[\Override]
    public function getKey(): string
    {
        return $this->node->getAttributeNS(XliffFile::XLIFF_NS, 'id');
    }

    #[\Override]
    public function getSource(): ?string
    {
        if (($element = $this->getSourceElement()) && $element->firstChild) {
            return $element->firstChild->nodeValue;
        }

        return null;
    }

    #[\Override]
    public function getTarget(): ?string
    {
        if (($element = $this->getTargetElement()) && $element->firstChild) {
            return $element->firstChild->nodeValue;
        }

        return null;
    }

    #[\Override]
    public function isSourceEmpty(): bool
    {
        return (null === ($element = $this->getSourceElement()) || null === $element->firstChild);
    }

    #[\Override]
    public function isTargetEmpty(): bool
    {
        return (null === ($element = $this->getTargetElement()) || null === $element->firstChild);
    }

    /** Fetch the source element. */
    protected function getSourceElement(): ?XmlElement
    {
        $list = $this->node->getElementsByTagNameNS(XliffFile::XLIFF_NS, 'source');
        if ($list->length && $element = $list->item(0)) {
            /** @var XmlElement $element */
            return $element;
        }

        return null;
    }

    /** Fetch the target element. */
    protected function getTargetElement(): ?XmlElement
    {
        $list = $this->node->getElementsByTagNameNS(XliffFile::XLIFF_NS, 'target');
        if ($list->length && $element = $list->item(0)) {
            /** @var XmlElement $element */
            return $element;
        }

        return null;
    }
}
