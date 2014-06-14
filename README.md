# eZ Publish IO Legacy Dispatcher Bundle

This bundle integrates the legacy extension ezsystems/ezdfs-fsbackend-dispatcher into Symfony2. It makes installation
easier by abstracting some of the configuration and automating activation in legacy.

## Requirements
This bundle relies on legacy being able to load FS Backends from services configured in INI files, as implemented in
(this commit)[https://github.com/bdunogier/ezpublish-legacy/commit/671e6f4399247126819a567290601c0f853bdabf].

## Installation

### Using composer
Not available yet.

### Manually

Checkout this repository in the src folder:
```shell
cd src
git clone git@github.com:bdunogier/io-legacy-dispatcher-bundle.git eZ/Bundles/IOLegacyDispatcherBundle
```

### Enabling the bundle
Edit `ezpublish/EzPublishKernel.php`, and add the bundle to the list of enabled ones:
```php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Ez\Bundles\IOLegacyDispatcherBundle\EzBundlesIOLegacyDispatcherBundle(),
    );
```

## Configuration
No semantic configuration is exposed yet.

FS Backends mapping can be configured using service container configuration.
`ezpublish_legacy.dfs.backend.dispatcher.handlerMap` is a hash of `eZDFSFileHandlerDFSBBackendInterface`, with the
index set to the mapped path, like the original INI configuration.

## Things to know
Due to the low level nature of cluster handlers in legacy, cluster handlers are required *during* the kernel's
initialization, and can't use the kernel nor services at this point. Since this bundle *changes* the cluster's config,
an event, triggered post kernel build, will (reset the cluster handlers)[LegacyMapper/Configuration.php:85] so that they
can be re-created using the new configuration.

# License
See the LICENSE file that should come with this extension.
