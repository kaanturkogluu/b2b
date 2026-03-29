<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = User::getRoles();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,personel',
        ], [
            'name.required' => 'Ad soyad alanı zorunludur.',
            'name.max' => 'Ad soyad en fazla 255 karakter olabilir.',
            'username.required' => 'Kullanıcı adı alanı zorunludur.',
            'username.unique' => 'Bu kullanıcı adı zaten kullanılıyor.',
            'username.max' => 'Kullanıcı adı en fazla 255 karakter olabilir.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            'email.max' => 'E-posta adresi en fazla 255 karakter olabilir.',
            'password.required' => 'Şifre alanı zorunludur.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',
            'role.required' => 'Rol seçimi zorunludur.',
            'role.in' => 'Geçersiz rol seçimi.',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->filled('email') ? $request->email : null,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Activity log
            ActivityLog::log(
                'user_created',
                "Yeni kullanıcı oluşturuldu: {$user->name} ({$user->role})",
                Auth::id(),
                $user->id,
                'App\Models\User',
                [
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role
                ]
            );

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Kullanıcı oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $roles = User::getRoles();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,personel',
        ], [
            'name.required' => 'Ad soyad alanı zorunludur.',
            'name.max' => 'Ad soyad en fazla 255 karakter olabilir.',
            'username.required' => 'Kullanıcı adı alanı zorunludur.',
            'username.unique' => 'Bu kullanıcı adı zaten kullanılıyor.',
            'username.max' => 'Kullanıcı adı en fazla 255 karakter olabilir.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            'email.max' => 'E-posta adresi en fazla 255 karakter olabilir.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',
            'role.required' => 'Rol seçimi zorunludur.',
            'role.in' => 'Geçersiz rol seçimi.',
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->filled('email') ? $request->email : null,
                'role' => $request->role,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Activity log
            ActivityLog::log(
                'user_updated',
                "Kullanıcı güncellendi: {$user->name} ({$user->username})",
                Auth::id(),
                $user->id,
                'App\Models\User',
                [
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role
                ]
            );

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'Kullanıcı başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Kullanıcı güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Kendi hesabınızı silemezsiniz.');
        }

        DB::beginTransaction();
        try {
            // Store user data before deletion for activity log
            $userData = [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at?->format('Y-m-d H:i:s')
            ];

            $user->delete();

            // Activity log
            ActivityLog::log(
                'user_deleted',
                "Kullanıcı silindi: {$userData['name']} ({$userData['username']}) - {$userData['role']}",
                Auth::id(),
                null, // related_id is null since record is deleted
                'App\Models\User',
                $userData
            );

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'Kullanıcı başarıyla silindi.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('users.index')
                ->with('error', 'Kullanıcı silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(User $user)
    {
        // Prevent admin from deactivating themselves
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Kendi hesabınızı deaktive edemezsiniz.');
        }

        DB::beginTransaction();
        try {
            $user->update([
                'is_active' => !$user->is_active
            ]);

            // Activity log
            $status = $user->is_active ? 'aktif' : 'pasif';
            ActivityLog::log(
                'user_status_toggled',
                "Kullanıcı durumu değiştirildi: {$user->name} ({$user->username}) - {$status}",
                Auth::id(),
                $user->id,
                'App\Models\User',
                [
                    'name' => $user->name,
                    'username' => $user->username,
                    'new_status' => $status
                ]
            );

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', "Kullanıcı {$status} hale getirildi.");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('users.index')
                ->with('error', 'Kullanıcı durumu değiştirilirken bir hata oluştu: ' . $e->getMessage());
        }
    }

}
