<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\UsersController;

class UsersController extends Controller
{
    private function onlyAdmin(Request $request): void
    {
        abort_unless($request->user()->isAdmin(), 403);
    }

    public function index(Request $request)
    {
        $this->onlyAdmin($request);
        return view('panel.users.index', [
            'users' => User::orderBy('role')->orderBy('name')->get(),
        ]);
    }

    public function create(Request $request)
    {
        $this->onlyAdmin($request);
        if (User::where('role', UserRole::Agent->value)->exists()) {
            return redirect()->route('users.index')->with('ok', 'Ya existe un agente. Puedes editarlo.');
        }
        return view('panel.users.form', ['user' => null]);
    }

    public function store(Request $request)
    {
        $this->onlyAdmin($request);
        if (User::where('role', UserRole::Agent->value)->exists()) {
            return redirect()->route('users.index')->with('ok', 'Ya existe un agente. Puedes editarlo.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', Password::min(10)],
        ]);

        User::create($data + [
            'role' => UserRole::Agent,
            'is_active' => true,
            'must_change_password' => true,
        ]);

        return redirect()->route('users.index')->with('ok', 'Agente creado. Deberá cambiar su contraseña al entrar.');
    }

    public function edit(Request $request, User $user)
    {
        $this->onlyAdmin($request);
        abort_if($user->isAdmin(), 404);
        return view('panel.users.form', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $this->onlyAdmin($request);
        abort_if($user->isAdmin(), 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', Password::min(10)],
        ]);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if (! empty($data['password'])) {
            $user->password = $data['password'];
            $user->must_change_password = true;
        }
        $user->save();

        return redirect()->route('users.index')->with('ok', 'Agente actualizado.');
    }
}
