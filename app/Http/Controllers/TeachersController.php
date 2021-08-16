<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\MTeachersModel;
use App\Models\MUsersModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TeachersController extends Controller
{
    public function __construct()
    {
        //
    }

    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'user_email' => 'required|string|unique:m_users',
                'user_username' => 'required|string|unique:m_users',
                'user_password' => 'required|string',
                'teachers_name' => 'required|string',
                'teachers_telpn' => 'required|string',
                'teachers_address' => 'required|string',
                'teachers_birth_place' => 'required|string',
                'teachers_birth_date' => 'required|date_format:Y-m-d',
            ]);

            $user_model = new MUsersModel;
            $user_model->user_email = $request->user_email;
            $user_model->user_username = $request->user_username;
            $user_model->user_password_str = $request->user_password;
            $user_model->password = Hash::make($user_model->user_password_str);
            $user_model->user_role_id = 3;
            $user_model->user_role_name = 'Teacher';

            if(!$user_model->save()){
                throw new \Exception("Gagal Menyimpan Data User");
            }
            
            $teacher_model = new MTeachersModel;
            $teacher_model->teachers_name = $request->teachers_name;
            $teacher_model->teachers_telpn = $request->teachers_telpn;
            $teacher_model->teachers_address = $request->teachers_address;
            $teacher_model->teachers_birth_place = $request->teachers_birth_place;
            $teacher_model->teachers_birth_date = $request->teachers_birth_date;
            $teacher_model->teachers_user_id = $user_model->user_id;
            if(!$teacher_model->save()){
                throw new \Exception("Gagal Menyimpan Data User");
            }

            DB::commit();
            $response = $request->all();
            return $this->success_response("Berhasil Menyimpan Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }

    public function list(Request $request)
    {
        DB::beginTransaction();
        try {
            $search = $request->search;
            $limit = $request->limit;
            $offset = $request->offset;

            $query = MTeachersModel::select('m_teachers.*', 'm_users.*')->join('m_users', 'm_users.user_id' ,'=', 'm_teachers.teachers_user_id');
            if($limit){
                $query = $query->limit($limit);
            }

            if($offset){
                $query = $query->offset($offset);
            }

            if($search){
                $query = $query->where('teachers_name', 'LIKE', "%{$search}%") 
                                ->orWhere('user_email', 'LIKE', "%{$search}%")
                                ->orWhere('user_username', 'LIKE', "%{$search}%");
            }
            $list_teacer = $query->get();
            $response = [
                'list_teacher'=>$list_teacer,
                'count_teacher' => count($list_teacer)
            ];
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }

    public function detail(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'teachers_id' => 'required|numeric',
            ]);

            $teacher = MTeachersModel::select('m_teachers.*', 'm_users.*')->join('m_users', 'm_users.user_id' ,'=', 'm_teachers.teachers_user_id')->where('m_teachers.teachers_id', $request->teachers_id)->first();
            $response = [
                'detail_teacher'=>$teacher,
            ];
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'user_email' => 'required|string',
                'user_username' => 'required|string',
                'user_password' => 'required|string',
                'teachers_name' => 'required|string',
                'teachers_telpn' => 'required|string',
                'teachers_id' => 'required|numeric',
                'teachers_address' => 'required|string',
                'teachers_birth_place' => 'required|string',
                'teachers_birth_date' => 'required|date_format:Y-m-d',
            ]);

            
            $teacher_model = MTeachersModel::find($request->teachers_id);
            $teacher_model->teachers_name = $request->teachers_name;
            $teacher_model->teachers_telpn = $request->teachers_telpn;
            $teacher_model->teachers_address = $request->teachers_address;
            $teacher_model->teachers_birth_place = $request->teachers_birth_place;
            $teacher_model->teachers_birth_date = $request->teachers_birth_date;

            if(!$teacher_model->save()){
                throw new \Exception("Gagal Menyimpan Data User");
            }

            $user_model = MUsersModel::find($teacher_model->teachers_user_id);
            $user_model->user_email = $request->user_email;
            $user_model->user_username = $request->user_username;
            $user_model->user_password_str = $request->user_password;
            $user_model->password = Hash::make($user_model->user_password_str);
            $user_model->user_role_id = 3;
            $user_model->user_role_name = 'Teacher';

            if(!$user_model->save()){
                throw new \Exception("Gagal Menyimpan Data User");
            }

            DB::commit();
            $response = $request->all();
            return $this->success_response("Berhasil Menyimpan Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }

    public function edit_status_active(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'teachers_id' => 'required|numeric',
                'teachers_status_active' => 'required|numeric|in:0,1',
            ]);

            $teacher = MTeachersModel::where('m_teachers.teachers_id', $request->teachers_id)->first();
            $teacher->user->user_status_active = $request->teachers_status_active;
            if(!$teacher->user->save()){
                throw new \Exception("Gagal Menyimpan Data User");
            }
            $response = [
                'detail_teacher'=>$teacher,
            ];
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }

    public function edit_status_verifikasi(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'teachers_id' => 'required|numeric',
                'teachers_status_varifikasi' => 'required|numeric|in:0,1',
            ]);

            $teacher = MTeachersModel::where('m_teachers.teachers_id', $request->teachers_id)->first();
            $teacher->user->user_status_verifikasi = $request->teachers_status_varifikasi;
            if(!$teacher->user->save()){
                throw new \Exception("Gagal Menyimpan Data User");
            }
            $response = [
                'detail_teacher'=>$teacher,
            ];
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }
}
