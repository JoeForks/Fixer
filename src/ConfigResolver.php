<?php

/*
 * This file is part of StyleCI Fixer.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Fixer;

use StyleCI\Config\ConfigFactory;
use Symfony\CS\Config\Config;
use Symfony\CS\ConfigurationResolver;
use Symfony\CS\Finder\DefaultFinder;
use Symfony\CS\FixerInterface;

/**
 * This is the fixer resolver class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ConfigResolver
{
    /**
     * The config factory instance.
     *
     * @var \StyleCI\Config\ConfigFactory
     */
    protected $factory;

    /**
     * Create a new config resolver instance.
     *
     * @param \StyleCI\Config\ConfigFactory $factory
     *
     * @return void
     */
    public function __construct(ConfigFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Get the php-cs-fixer config object for the repo at the given path.
     *
     * @param string $path
     *
     * @return \Symfony\CS\Config\Config
     */
    public function resolve($path)
    {
        $fixers = $this->getConfigObject($path)->getFixers();

        $config = Config::create()->level(FixerInterface::NONE_LEVEL)->fixers($fixers);
        $config->finder(DefaultFinder::create()->notName('*.blade.php')->exclude('storage')->in($path));
        $config->setDir($path);

        $resolver = new ConfigurationResolver();
        $resolver->setAllFixers($this->fixer->getFixers())->setConfig($config)->resolve();

        $config->fixers($resolver->getFixers());

        return $config;
    }

    /**
     * Get the styleci config object for the repo at the given path.
     *
     * @param string $path
     *
     * @return \StyleCI\Config\Config
     */
    protected function getConfigObject($path)
    {
        $configPath = $path.'/.styleci.yml';

        if (file_exists($configPath)) {
            return $this->factory->makeFromYaml(file_get_contents($configPath));
        }

        return $this->factory->make();
    }
}
