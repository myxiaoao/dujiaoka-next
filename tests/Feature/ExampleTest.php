<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */
test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
