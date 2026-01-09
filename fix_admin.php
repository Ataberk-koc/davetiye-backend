<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('email', 'admin@admin.com')->first();

if (!$user) {
    $user = \App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => \Hash::make('admin'),
    ]);
    echo "Yeni admin user oluşturuldu!\n";
} else {
    $user->update([
        'password' => \Hash::make('admin'),
    ]);
    echo "Admin user'ın şifresi güncellendi!\n";
}

echo "Email: " . $user->email . "\n";
echo "Name: " . $user->name . "\n";
echo "Şimdi /admin/login adresine gidin ve giriş yapın.\n";
?>
