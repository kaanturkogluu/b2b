<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin 
                            {--name= : Kullanıcı adı}
                            {--username= : Kullanıcı adı (username)}
                            {--email= : E-posta adresi}
                            {--password= : Şifre}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sisteme admin kullanıcısı oluşturur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Admin kullanıcısı oluşturuluyor...');
        $this->newLine();

        // Get user input
        $name = $this->option('name') ?: $this->ask('Ad Soyad', 'Admin User');
        $username = $this->option('username') ?: $this->ask('Kullanıcı Adı (username)', 'admin');
        $email = $this->option('email') ?: $this->ask('E-posta Adresi (opsiyonel)', null);
        $password = $this->option('password') ?: $this->secret('Şifre');

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            $this->error('Hata: Geçersiz bilgiler!');
            foreach ($validator->errors()->all() as $error) {
                $this->error('  - ' . $error);
            }
            return 1;
        }

        // Check if username already exists
        if (User::where('username', $username)->exists()) {
            $this->error("Hata: '{$username}' kullanıcı adı zaten kullanılıyor!");
            return 1;
        }

        // Check if email already exists (if provided)
        if ($email && User::where('email', $email)->exists()) {
            $this->error("Hata: '{$email}' e-posta adresi zaten kullanılıyor!");
            return 1;
        }

        // Create admin user
        try {
            $user = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $email ?: null,
                'password' => Hash::make($password),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
            ]);

            $this->newLine();
            $this->info('✓ Admin kullanıcısı başarıyla oluşturuldu!');
            $this->newLine();
            $this->table(
                ['Alan', 'Değer'],
                [
                    ['ID', $user->id],
                    ['Ad Soyad', $user->name],
                    ['Kullanıcı Adı', $user->username],
                    ['E-posta', $user->email ?: '(Belirtilmemiş)'],
                    ['Rol', $user->role],
                    ['Durum', $user->is_active ? 'Aktif' : 'Pasif'],
                ]
            );

            return 0;
        } catch (\Exception $e) {
            $this->error('Hata: Kullanıcı oluşturulurken bir hata oluştu!');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
