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

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Retry\RetrySubscriber;

/**
 * This is the downloader class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Fixer/blob/master/LICENSE.md> Apache 2.0
 */
class Downloader
{
    /**
     * Download and save the file.
     *
     * @param string $file
     * @param string $path
     *
     * @return \GrahamCampbell\Fixer\Zip\Archive
     */
    public function download($file, $path)
    {
        if (!is_dir(dirname($path))) {
            @mkdir(dirname($path));
        }

        if (is_file($path)) {
            @unlink($file);
        }

        $this->client()->get($file, ['save_to' => $path]);

        return new Archive($path);
    }

    /**
     * Get the guzzle client.
     *
     * @return \GuzzleHttp\Client
     */
    public function client()
    {
        if (!$this->client) {
            $this->client = new Client(['base_url' => 'https://codeload.github.com']);
            $this->attachRetrySubscriber();
        }

        return $this->client;
    }

    /**
     * Attach the retry subscriber to the guzzle client.
     *
     * @return void
     */
    protected function attachRetrySubscriber()
    {
        $filter = RetrySubscriber::createChainFilter([
            RetrySubscriber::createIdempotentFilter(),
            RetrySubscriber::createStatusFilter(),
        ]);

        $subscriber = new RetrySubscriber(['filter' => $filter]);

        $this->client->getEmitter()->attach($subscriber);
    }
}
