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

namespace GrahamCampbell\Fixer\Zip;

use Exception;

/**
 * This is the archive exception class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Fixer/blob/master/LICENSE.md> Apache 2.0
 */
class ArchiveException extends Exception
{
    /**
     * The file path.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new zip instance.
     *
     * @param string $path
     * @param string $message
     *
     * @return void
     */
    public function __construct($path, $message)
    {
        $this->path = $path;

        parent::__construct($message);
    }

    /**
     * Get the file path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
