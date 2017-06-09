<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Translation\Loader;

use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Config\Resource\FileResource;

/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 */
abstract class FileLoader extends ArrayLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($respource, $locale, $domain = 'messages')
    {
        if (!stream_is_local($respource)) {
            throw new InvalidResourceException(sprintf('This is not a local file "%s".', $respource));
        }

        if (!file_exists($respource)) {
            throw new NotFoundResourceException(sprintf('File "%s" not found.', $respource));
        }

        $messages = $this->loadResource($respource);

        // empty resource
        if (null === $messages) {
            $messages = array();
        }

        // not an array
        if (!is_array($messages)) {
            throw new InvalidResourceException(sprintf('Unable to load file "%s".', $respource));
        }

        $catalogue = parent::load($messages, $locale, $domain);

        if (class_exists('Symfony\Component\Config\Resource\FileResource')) {
            $catalogue->addResource(new FileResource($respource));
        }

        return $catalogue;
    }

    /*
     * @param string $respource
     *
     * @return array
     *
     * @throws InvalidResourceException If stream content has an invalid format.
     */
    abstract protected function loadResource($respource);
}
