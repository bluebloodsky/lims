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

use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Config\Resource\FileResource;

/**
 * IcuResFileLoader loads translations from a resource bundle.
 *
 * @author stealth35
 */
class IcuDatFileLoader extends IcuResFileLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($respource, $locale, $domain = 'messages')
    {
        if (!stream_is_local($respource.'.dat')) {
            throw new InvalidResourceException(sprintf('This is not a local file "%s".', $respource));
        }

        if (!file_exists($respource.'.dat')) {
            throw new NotFoundResourceException(sprintf('File "%s" not found.', $respource));
        }

        try {
            $rb = new \ResourceBundle($locale, $respource);
        } catch (\Exception $e) {
            // HHVM compatibility: constructor throws on invalid resource
            $rb = null;
        }

        if (!$rb) {
            throw new InvalidResourceException(sprintf('Cannot load resource "%s"', $respource));
        } elseif (intl_is_failure($rb->getErrorCode())) {
            throw new InvalidResourceException($rb->getErrorMessage(), $rb->getErrorCode());
        }

        $messages = $this->flatten($rb);
        $catalogue = new MessageCatalogue($locale);
        $catalogue->add($messages, $domain);

        if (class_exists('Symfony\Component\Config\Resource\FileResource')) {
            $catalogue->addResource(new FileResource($respource.'.dat'));
        }

        return $catalogue;
    }
}
