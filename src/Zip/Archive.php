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

use ZipArchive;

/**
 * This is the zip archive class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Fixer/blob/master/LICENSE.md> Apache 2.0
 */
class Archive
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
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Destroy the zip archive instance.
     *
     * We're making sure we release the resources here.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Extract the archive.
     *
     * @param string $dir
     *
     * @throws \GrahamCampbell\Fixer\Zip\ExtractingException
     *
     * @return $this
     */
    public function extract($dir)
    {
        if (!$this->get()->extractTo($dir)) {
            throw new ExtractingException($this->path);
        }

        return $this;
    }

    /**
     * Delete the archive.
     *
     * @throws \GrahamCampbell\Fixer\Zip\DeletingException
     *
     * @return $this
     */
    public function delete()
    {
        $this->close();

        if (!@unlink($this->path)) {
            throw new DeletingException($this->path);
        }

        return $this;
    }

    /**
     * Get the archive instance, ready to use.
     *
     * @throws \GrahamCampbell\Fixer\Zip\OpeningException
     *
     * @return \ZipArchive
     */
    public function get()
    {
        if (!$this->archive) {
            $archive = new ZipArchive();
            if (!$archive->open($this->path)) {
                throw new OpeningException($this->path);
            }
            $this->archive = $archive;
        }

        return $this->archive;
    }

    /**
     * Close the archive.
     *
     * @throws \GrahamCampbell\Fixer\Zip\ClosingException
     *
     * @return $this
     */
    public function close()
    {
        if ($this->archive) {
            if (!$this->archive->close()) {
                throw new ClosingException($this->path);
            }
            $this->archive = null;
        }

        return $this;
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
