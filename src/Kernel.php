<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * {@inheritdoc}
     */
    public function registerBundles(): iterable
    {
        $contents = require $this->getBundlesPath();
        if (in_array($this->environment, ['dev', 'test'])) {
            $devContents = require $this->getBundlesDevPath();
            $contents = array_merge($contents, $devContents);
        }
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    /**
     * Gets the path to the bundles configuration file.
     */
    private function getBundlesPath(): string
    {
        return $this->getConfigDir() . '/bundles.php';
    }

    /**
     * Gets the path to the bundles configuration file.
     */
    private function getBundlesDevPath(): string
    {
        return $this->getConfigDir() . '/dev/bundles.php';
    }
}
