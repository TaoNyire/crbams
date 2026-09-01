<?php

it('redirects visitors from the application entry point to the dashboard', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('dashboard'));
});
