<?php

/*
 * This file is part of StyleCI Fixer.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Tests\Fixer;

use GrahamCampbell\TestBench\Traits\ServiceProviderTestCaseTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTestCaseTrait;

    public function testAnalyserIsInjectable()
    {
        $this->assertIsInjectable('StyleCI\Fixer\Analyser');
    }

    public function testReportBuilderIsInjectable()
    {
        $this->assertIsInjectable('StyleCI\Fixer\ReportBuilder');
    }
}
