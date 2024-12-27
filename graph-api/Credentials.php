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

namespace Strongholdam\Microsoft\GraphApi;

class Credentials
{
    public function __construct(public string $TENANT_ID, public string $CLIENT_ID, public string $CLIENT_SECRET, public string $REFRESH_TOKEN)
    {
    }
}
