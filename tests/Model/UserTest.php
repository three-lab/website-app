<?php

use App\Models\User;

use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;

beforeEach(function () {
    $this->userModel = new User();
    $this->userData = [
        'name' => 'test',
        'email' => 'test@email',
        'phone' => '62812345678',
        'password' => password('test'),
        'profile' => 'test.png',
    ];
});

test('can add new user', function () {
    $user = $this->userModel->insert($this->userData);

    assertNotNull($user->id);
    expect($user)->toBeInstanceOf(User::class);
});

test('can edit user', function () {
    $user = $this->userModel->insert($this->userData);
    $user->update(['email' => 'test2@mail.com']);

    $user = $this->userModel->find($user->id);

    assertNotSame($user->email, $this->userData['email']);
    assertSame($user->email, 'test2@mail.com');
});

test('can delete user', function() {
    $user = $this->userModel->insert($this->userData);

     assertGreaterThan(0, $user->delete());
     assertNull($this->userModel->find($user->id));
});
