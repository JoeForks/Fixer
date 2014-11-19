<?php

/**
 * This file is part of Laravel Fixer by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://bit.ly/UWsjkb.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Fixer\Analysers;

/**
 * This is the analyser class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Fixer/blob/master/LICENSE.md> Apache 2.0
 */
class Analyser
{
    /**
     * The code style analyser instance.
     *
     * @var \GrahamCampbell\Fixer\Analysers\CodeStyle
     */
    protected $cs;

    /**
     * Create an analyser instance.
     *
     * @param \GrahamCampbell\Fixer\Analysers\CodeStyle $cs
     *
     * @return void
     */
    public function __construct(CodeStyle $cs)
    {
        $this->cs = $cs;
    }

    /**
     * Analyse the commit.
     *
     * @param string $path
     *
     * @return array
     */
    public function analyse($path)
    {
        $data = $this->cs->analyse($path);

        return $data;
    }
}
