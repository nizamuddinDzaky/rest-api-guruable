<?php

namespace App\Http\Controllers;

// use Laravel\Lumen\Routing\Controller ;

use App\Models\MClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Yajra\DataTables\DataTablesServiceProvider;
use DataTables;
class ClassController extends Controller
{
    public function add(Request $request)
    {
        DB::beginTransaction();
        try {

            $this->validate($request, [
                'class_name' => 'required|string',
                'class_code' =>'required|string|unique:m_class',
            ]);

            $class_model = new MClassModel;
            $class_model->class_name = $request->class_name;
            $class_model->class_status = $request->class_status ?? 1;
            $class_model->class_code = $request->class_code;
            if(!$class_model->save()){
                throw new \Exception("Gagal Menyimpan Data Kelas");
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
            $status = $request->class_status ;

            $query = MClassModel::select('m_class.*');
            if($status != null) {
                $query = $query->where('class_status', $status);
            }
            $record_total = $query->count();
            if($search){
                $query = $query->where('class_name', 'LIKE', "%{$search}%") 
                                ->orWhere('class_code', 'LIKE', "%{$search}%");
            }

            $response = $this->build_respone_data_table($query, $limit, $offset, $record_total);
            DB::commit();
            return $this->success_response("Berhasil Mengambil Data", $response, $request->all());
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
                'class_id' => 'required|numeric',
            ]);

            $class = MClassModel::select('m_class.*')->where('m_class.class_id', $request->class_id)->first();
            if(!$class){
                throw new \Exception("Data Kelas Tidak Ditemukan");
            }
            $response = [
                'detail_class'=>$class,
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
                'class_id' => 'required|string',
                'class_name' => 'required|string',
                'class_code' =>'required|string',
            ]);

            $class_model = MClassModel::find($request->class_id);
            $class_model->class_name = $request->class_name;
            $class_model->class_status = $request->class_status ?? 1;
            $class_model->class_code = $request->class_code;
            if(!$class_model->save()){
                throw new \Exception("Gagal Menyimpan Data Kelas");
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
                'class_id' => 'required|numeric',
                'class_status' => 'required|numeric|in:0,1',
            ]);

            $class_model = MClassModel::find($request->class_id);

            if(!$class_model){
                throw new \Exception("Class ID ".$request->class_id);
            }

            $class_model->class_status = $request->class_status;
            if(!$class_model->save()){
                throw new \Exception("Gagal Menyimpan Data User");
            }
            $response = [
                'detail_teacher'=>$class_model,
            ];
            DB::commit();
            return $this->success_response("Berhasil Mengambil Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }
}