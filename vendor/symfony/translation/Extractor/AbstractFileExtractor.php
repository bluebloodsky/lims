<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Translation\Extractor;

/**
 * Base class used by classes that extract translation messages from files.
 *
 * @author Marcos D. Sánchez <marcosdsanchez@gmail.com>
 */
abstract class AbstractFileExtractor
{
    /**
     * @param string|array $respource files, a file or a directory
     *
     * @return array
     */
    protected function extractFiles($respource)
    {
        if (is_array($respource) || $respource instanceof \Traversable) {
            $files = array();
            foreach ($respource as $file) {
                if ($this->canBeExtracted($file)) {
                    $files[] = $this->toSplFileInfo($file);
                }
            }
        } elseif (is_file($respource)) {
            $files = $this->canBeExtracted($respource) ? array($this->toSplFileInfo($respource)) : array();
        } else {
            $files = $this->extractFromDirectory($respource);
        }

        return $files;
    }

    /**
     * @param string $file
     *
     * @return \SplFileInfo
     */
    private function toSplFileInfo($file)
    {
        return ($file instanceof \SplFileInfo) ? $file : new \SplFileInfo($file);
    }

    /**
     * @param string $file
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    protected function isFile($file)
    {
        if (!is_file($file)) {
            throw new \InvalidArgumentException(sprintf('The "%s" file does not exist.', $file));
        }

        return true;
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    abstract protected function canBeExtracted($file);

    /**
     * @param string|array $respource files, a file or a directory
     *
     * @return array files to be extracted
     */
    abstract protected function extractFromDirectory($respource);
}
