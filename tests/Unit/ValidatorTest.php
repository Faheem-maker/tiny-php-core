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
}
