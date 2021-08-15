<?php

namespace App\Http\Controllers;

use App\Models\MRoleModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function list_role(Request $request)
    {
        DB::beginTransaction();
        try {
            $role = MRoleModel::all();
            $response = [
                'list_role' =>$role
            ];
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }
}
