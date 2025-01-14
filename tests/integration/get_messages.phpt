--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

exceptionMessages(static function (): void {
    v::create()
        ->key(
            'mysql',
            v::create()
                ->key('host', v::stringType())
                ->key('user', v::stringType())
                ->key('password', v::stringType())
                ->key('schema', v::stringType())
        )
        ->key(
            'postgresql',
            v::create()
                ->key('host', v::stringType())
                ->key('user', v::stringType())
                ->key('password', v::stringType())
                ->key('schema', v::stringType())
        )
        ->assert([
            'mysql' => [
                'host' => 42,
                'schema' => 42,
            ],
            'postgresql' => [
                'user' => 42,
                'password' => 42,
            ],
        ]);
});
?>
--EXPECT--
[
    'mysql' => [
        'host' => 'host must be of type string',
        'user' => 'user must be present',
        'password' => 'password must be present',
        'schema' => 'schema must be of type string',
    ],
    'postgresql' => [
        'host' => 'host must be present',
        'user' => 'user must be of type string',
        'password' => 'password must be of type string',
        'schema' => 'schema must be present',
    ],
]
