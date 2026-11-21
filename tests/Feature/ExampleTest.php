<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('homepage displays the PostHub landing page', function () {

    $response = $this->get('/');

    $response->assertOk();

    $response->assertViewIs('home');

    $response->assertSeeText('Discover ideas.');

});
