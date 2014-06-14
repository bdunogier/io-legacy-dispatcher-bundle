<?php
/**
 * File containing the Configuration class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

namespace Ez\Bundles\IOLegacyDispatcherBundle\LegacyMapper;

use eZ\Publish\Core\MVC\Legacy\Event\PostBuildKernelEvent;
use eZ\Publish\Core\MVC\Legacy\LegacyEvents;
use eZ\Publish\Core\MVC\Legacy\Event\PreBuildKernelEvent;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\Core\MVC\Symfony\Cache\GatewayCachePurger;
use eZ\Bundle\EzPublishLegacyBundle\Cache\PersistenceCachePurger;
use eZ\Publish\Core\MVC\Symfony\Routing\Generator\UrlAliasGenerator;
use eZ\Publish\Core\Persistence\Database\DatabaseHandler;
use eZClusterFileHandler;
use eZDFSFileHandler;
use eZDFSFileHandlerBackendFactory;
use ezpEvent;
use ezxFormToken;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use RuntimeException;

/**
 * Injects default extension configuration into legacy
 */
class Configuration extends ContainerAware implements EventSubscriberInterface
{
    /**
     * Disables the feature when set using setEnabled()
     *
     * @var bool
     */
    private $enabled = true;

    /**
     * Toggles the feature
     *
     * @param bool $isEnabled
     */
    public function setEnabled( $isEnabled )
    {
        $this->enabled = (bool)$isEnabled;
    }

    public static function getSubscribedEvents()
    {
        return array(
            LegacyEvents::PRE_BUILD_LEGACY_KERNEL => array( "onBuildKernel", 128 ),
            LegacyEvents::POST_BUILD_LEGACY_KERNEL => array( "onPostBuildKernel", 128 )
        );
    }

    /**
     * Adds settings to the parameters that will be injected into the legacy kernel
     *
     * @param \eZ\Publish\Core\MVC\Legacy\Event\PreBuildKernelEvent $event
     */
    public function onBuildKernel( PreBuildKernelEvent $event )
    {
        if ( !$this->enabled )
        {
            return;
        }

        // User settings
//        $settings["file.ini/eZDFSClusteringSettings/DFSBackend"] = 'eZDFSFileHandlerDFSDispatcher';
//        $settings["file.ini/DispatchableDFS/DefaultBackend"] = 'eZDFSFileHandlerDFSBackend';
//        $settings["file.ini/DispatchableDFS/PathBackends"] = array();
        $settings['file.ini/eZDFSClusteringSettings/DFSBackend'] = '@ezpublish_legacy.dfs.backend.dispatcher';

        $event->getParameters()->set(
            "injected-settings",
            $settings + (array)$event->getParameters()->get( "injected-settings" )
        );
    }

    public function onPostBuildKernel( PostBuildKernelEvent $event )
    {
        eZDFSFileHandler::resetDBBackend();
    }
}
