<?php

/*
 * This file is part of the SDA package
 *
 * Copyright (c) 2020-2024 STRONGHOLD ASSET MANAGEMENT
 * All right reserved
 *
 * @author Álvaro Cebrián <acebrian@strongholdam.com>
 * @author Daniel González <dgonzalez@strongholdam.com>
 * @author Raúl Callado <rcallado@strongholdam.com>
 */

namespace Strongholdam\Microsoft\OneDrive;

use Strongholdam\Microsoft\GraphApi\MicrosoftApiService;

class OneDriveService
{
    private const BASE_PATH = '/v1.0/me/drive';

    public function __construct(private readonly MicrosoftApiService $microsoftApiService)
    {
    }

    /**
     * Push a file to remote OneDrive storage.
     *
     * @param string $localPath  Local file path
     * @param string $remotePath Remove file path
     */
    public function push(string $localPath, string $remotePath): void
    {
        $path = sprintf('%s/root:%s:/content', self::BASE_PATH, $remotePath);

        $response = $this->microsoftApiService->call(
            $path,
            'PUT',
            ['body' => fopen($localPath, 'r')],
        );

        if (!in_array($response->getStatusCode(), [200, 201])) { // NO OK Code
            throw new \Exception("Error pushing file");
        }
    }
}
