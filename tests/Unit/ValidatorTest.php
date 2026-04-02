<?php

use framework\models\attributes\Email;
use framework\models\attributes\Required;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testBasic()
    {
        $app = createApp();

        $this->assertEmpty($app->validator->validate((object) [
            'name' => 'John Doe',
            'email' => 'abc@gmail.com',
        ], [
            'name' => 'required',
            'email' => [Required::class, Email::class],
        ]));
    }

    public function testAddValidator()
    {
        $app = createApp();

        $app->validator->addValidator('custom', function ($value) {
            return $value === 'custom' ? null : 'This value must equal "custom"';
        });

        $this->assertEmpty($app->validator->validate((object) [
            'name' => 'custom',
        ], [
            'name' => 'custom',
        ]));

        $this->assertNotEmpty($app->validator->validate((object) [
            'name' => 'invalid',
        ], [
            'name' => 'custom',
        ]));
    }

    public function testDelimeter()
    {
        $app = createApp();

        $this->assertEmpty($app->validator->validate((object) [
            'email' => 'abc@gmail.com',
        ], [
            'email' => 'required|email',
        ]));

        $this->assertNotEmpty($app->validator->validate((object) [
            'email' => 'invalid-email',
        ], [
            'email' => 'required|email',
        ]));
    }
}
