<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User_Role;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Ui\Presets\React;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all()->where('record_state', '=', '1');
        $permission = Permission::all();
        return view('table_view.role', compact('roles', 'permission'));
    }
    // public function roles_data()
    // {
    //     $roles = DB::select('role_id, role_description');
    //     return response()->json(['data' => $roles]);
    // }
    public function get_roles($id)
    {
        try {
            $roles = Role::all();
            $countRoles = count($roles);
            $user_info = DB::table('user as u')->select(
                'p.person_name',
                'p.person_surname',
                'u.user_id',
            )
                ->join('person as p', 'u.person_id', '=', 'p.person_id')
                ->where('u.user_id', $id)->first();
            $fill = DB::table('user_role as ur')->select(
                'ur.id',
                'p.person_name',
                'ur.ur_state',
                'ur.user_id',
                'r.role_id as role_id',
                'r.role_description as role_description'
            )
                ->join('role as r', 'ur.role_id', '=', 'r.role_id')
                ->join('user as u', 'ur.user_id', '=', 'u.user_id')
                ->join('person as p', 'p.person_id', '=', 'u.person_id')
                ->where('ur.user_id', $id)->get();

            $countFill = count($fill);
            if ($countFill == $countRoles) {
                $addRoleBtnState = 0;
            } else {
                $addRoleBtnState = 1;
            }
            return view('layout.role_table', compact('user_info', 'fill', 'addRoleBtnState'));
        } catch (\Exception $e) {
            $user_info = DB::table('user as u')->select(
                'p.person_name',
                'p.person_surname',
                'u.user_id',
            )
                ->join('person as p', 'u.person_id', '=', 'p.person_id')
                ->where('u.user_id', $id)->first();
            $fill = [];
            return view('layout.role_table', compact('user_info', 'fill'));
        }
    }
    public function change($ur_id)
    {
        $user_id = request('user_id');
        $state = request('state');

        $data = User_Role::find($ur_id);
        $data->ur_state = $state;
        $data->update();

        $getdata = $this->get_roles($user_id);
        return response($getdata);
    }

    //start section en proceso
    public function show_add_role()
    {
        try {
            $user_id = request('user_id');
            $user_info = DB::table('user as u')->select(
                'p.person_name',
                'u.user_id as user_id'
            )
                ->join('person as p', 'u.person_id', '=', 'p.person_id')
                ->where('u.user_id', $user_id)->first();

            $rolelist = Role::whereNotIn('role_id', function ($query) {
                $query->select('role_id')->from('user_role')->where('user_id', request('user_id'));
            })->get();
            return view('layout.add_role', compact('user_info', 'rolelist'));
        } catch (\Exception $e) {
            $user_id = request('user_id');
            $user_info = DB::table('user as u')->select(
                'p.person_name',
                'u.user_id as user_id'
            )
                ->join('person as p', 'u.person_id', '=', 'p.person_id')
                ->where('u.user_id', $user_id)->first();
            $rolelist = DB::table('role')
                ->select('role_id', 'role_description')
                ->orderBy('r.role_id')->get();
            return view('layout.add_role', compact('user_info', 'rolelist'));
        }
    }
    public function add_role(Request $request)
    {
        $record = $request->input('role');
        $user_id = $request->input('user_id');
        $record_lenght = count($record);

        for ($index = 0; $index < $record_lenght; $index++) {
            if (isset($record[$index])) {
                $row = new User_Role();
                $row->user_id = $user_id;
                $row->role_id = $request->input("role.{$index}");
                $row->save();
            }
        }
        return redirect()->back()->with('status', 'success');
    }
    public function del_role($id)
    {
        $user_role = DB::table('user_role')->where('user_id', $id)->get();
        foreach ($user_role as $role) {
            DB::table('user_role')->where('id', $role->id)->delete();
        }
        $data = Role::find($id);
        $data->record_state = 0;
        $data->update();
        return response()->json(['status', 'success']);
    }

    // public function create(Request $request)
    // {
    //     $request->validate([
    //         'arole' => 'required',
    //     ]);
    //     $newrole = $request->input('arole');
    //     $permissions = $request->input('check');
    //     if ($permissions) {
    //         $permission_lenght = count($permissions);
    //     } else {
    //         $permission_lenght = 0;
    //     }
    //     $role = DB::table('role')->where('role_description', $newrole)->first();
    //     if (!$role) {
    //         $role_data = new Role();
    //         $role_data->role_description = $newrole;
    //         $role_data->save();
    //         if ($permission_lenght != 0) {
    //             for ($index = 0; $index < $permission_lenght; $index++) {
    //                 if (isset($permissions[$index])) {
    //                     $role_permission_data = new Role_Permission();
    //                     $role_permission_data->role_id = $role_data->role_id;
    //                     $role_permission_data->permission_id = $request->input("check.{$index}");
    //                     $role_permission_data->save();
    //                 }
    //             }
    //         }
    //     } else {
    //         if ($permission_lenght != 0) {
    //             for ($index = 0; $index < $permission_lenght; $index++) {
    //                 if (isset($permissions[$index])) {
    //                     $role_permission_data = new Role_Permission();
    //                     $role_permission_data->role_id = $role->role_id;
    //                     $role_permission_data->permission_id = $request->input("check.{$index}");
    //                     $role_permission_data->save();
    //                 }
    //             }
    //         }
    //     }
    //     return redirect()->back()->with('status', 'registered');
    // }

    public function create(Request $request)
    {
        $description = $request->input('arole');
        $role_data = new Role();
        $role_data->role_description = $description;
        $role_data->save();
        return response()->json(['status' => 'success']);
    }

    public function check_role(Request $request)
    {
        $role = $request->input('description');

        $checked_role = DB::table('role')->where('role_description', '=', $role)->first();
        if (empty($checked_role)) {
            return response()->json(['status' => 'not_exists']);
        } else {
            return response()->json(['status' => 'exists']);
        }
    }
}
