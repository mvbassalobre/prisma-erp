<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use marcusvbda\vstack\Services\Messages;
use Auth;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function store(Request $request)
    {
        if (@$request["id"])
            $this->editUser($request);
        else
            $this->createUser($request);
        Messages::send("success", "Registro salvo com sucesso !!");
        return ["success" => true, "route" => route('resource.index', ["resource" => "users"])];
    }

    private function createUser($request)
    {
        $user = Auth::user();
        $roles = [
            'name'     => 'required',
            'email'    => ['required', 'email', Rule::unique('users')->whereNull('deleted_at')],
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];
        if ($user->hasRole(["super-admin"])) $roles['tenant_id'] = 'required';
        if ($user->hasRole(["super-admin", "admin"])) $roles['roleName'] = 'required';

        $this->validate($request, $roles);

        $data = $request->except(["_token", "password_confirmation", "roleName", "resource_id", "tenantName"]);

        if (!@$request["tenant_id"]) $data["tenant_id"] = $user->tenant_id;
        else $data["tenant_id"] = $request["tenant_id"];

        $data["avatar"] = is_array(@$data["avatar"]) ? @$data["avatar"][0] : "";
        $data["email_verified_at"] = date("Y-m-d H:i:s");
        $user = User::create($data);
        $user->assignRole($request["roleName"]);
    }

    private function editUser($request)
    {
        $_user = Auth::user();

        $roles = [
            'name'     => 'required',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($request['id'])->whereNull('deleted_at')],
            'password' => 'confirmed'
        ];

        if ($_user->hasRole(["super-admin"])) $roles['tenant_id'] = 'required';
        if ($_user->hasRole(["super-admin", "admin"])) $roles['roleName'] = 'required';

        $this->validate($request, $roles);

        $data = $request->except(["id", "_token", "password_confirmation", "roleName", "resource_id", "tenant_id"]);

        if (!$data["password"]) unset($data["password"]);
        $user = User::findOrFail($request["id"]);
        $data["avatar"] = is_array($data["avatar"]) ? (@$data["avatar"] ? $data["avatar"][0] : "") : "";

        if (!@$request["tenant_id"]) $data["tenant_id"] = $_user->tenant_id;
        else $data["tenant_id"] = $request["tenant_id"];
        $user->fill($data);
        $user->save();
        $user->roles()->detach();
        $user->assignRole($request["roleName"]);
    }

    public function profile(User $user)
    {
        $user = Auth::user()->only(["name", "email", "avatar"]);
        return view("admin.account.index", compact("user"));
    }

    public function editProfile(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required'
        ]);
        $user = Auth::user();
        $data = $request->all();
        $user->fill($data);
        $user->save();
        return ["success" => true];
    }
}
