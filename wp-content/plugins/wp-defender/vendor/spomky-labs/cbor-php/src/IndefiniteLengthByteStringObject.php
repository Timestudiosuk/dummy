<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2018-2020 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace CBOR;

/**
 * @final
 */
class IndefiniteLengthByteStringObject extends AbstractCBORObject implements Normalizable
{
    private const MAJOR_TYPE = self::MAJOR_TYPE_BYTE_STRING;

    private const ADDITIONAL_INFORMATION = self::LENGTH_INDEFINITE;

    /**
     * @var ByteStringObject[]
     */
    private $chunks = [];

    public function __construct()
    {
        parent::__construct(self::MAJOR_TYPE, self::ADDITIONAL_INFORMATION);
    }

    public function __toString(): string
    {
        $result = parent::__toString();
        foreach ($this->chunks as $chunk) {
            $result .= $chunk->__toString();
        }

        return $result . "\xFF";
    }

    public static function create(): self
    {
        return new self();
    }

    public function add(ByteStringObject $chunk): self
    {
        $this->chunks[] = $chunk;

        return $this;
    }

    public function append(string $chunk): self
    {
        $this->add(ByteStringObject::create($chunk));

        return $this;
    }

    public function getValue(): string
    {
        $result = '';
        foreach ($this->chunks as $chunk) {
            $result .= $chunk->getValue();
        }

        return $result;
    }

    public function getLength(): int
    {
        $length = 0;
        foreach ($this->chunks as $chunk) {
            $length += $chunk->getLength();
        }

        return $length;
    }

    public function normalize(): string
    {
        $result = '';
        foreach ($this->chunks as $chunk) {
            $result .= $chunk->normalize();
        }

        return $result;
    }

    /**
     * @deprecated The method will be removed on v3.0. Please rely on the CBOR\Normalizable interface
     */
    public function getNormalizedData(bool $ignoreTags = false): string
    {
        $result = '';
        foreach ($this->chunks as $chunk) {
            $result .= $chunk->getNormalizedData($ignoreTags);
        }

        return $result;
    }
}