<?php

namespace Tests\Feature;

use Tests\TestCase;

class TwoFactorAuthenticationSettingsTest extends TestCase
{
    public function test_two_factor_authentication_routes_are_not_part_of_the_breeze_application(): void
    {
        $this->get('/user/two-factor-authentication')->assertNotFound();
        $this->post('/user/two-factor-authentication')->assertNotFound();
        $this->get('/user/two-factor-recovery-codes')->assertNotFound();
    }
}
