<?php

namespace App\Http\Controllers;

// use Laravel\Lumen\Routing\Controller ;
use App\Models\MsubjectsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Yajra\DataTables\DataTablesServiceProvider;
use DataTables;
class SubjectsController extends Controller
{
    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'subject_name' => 'required|string',
                'subject_code' =>'required|string|unique:m_subjects',
            ]);

            $subject_model = new MsubjectsModel();
            $subject_model->subject_name =  $request->subject_name;
            $subject_model->subject_code =  $request->subject_code;
            $subject_model->subject_status = $request->subject_status ?? 1;

            if(!$subject_model->save()){
                throw new \Exception("Gagal Menyimpan Data Subject");
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
            $status = $request->subject_status ;

            $query = MsubjectsModel::select('m_subjects.*');
            if($status != null) {
                $query = $query->where('subject_status', $status);
            }
            $record_total = $query->count();
            
            if($search){
                $query = $query->where('subject_name', 'LIKE', "%{$search}%") 
                                ->orWhere('subject_code', 'LIKE', "%{$search}%");
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
                'subject_id' => 'required|numeric',
            ]);

            $subject = MsubjectsModel::select('m_subjects.*')->where('m_subjects.subject_id', $request->subject_id)->first();
            if(!$subject){
                throw new \Exception("Data Subjecet Tidak Ditemukan");
            }
            $response = [
                'detail_subject'=>$subject,
            ];
            DB::commit();
            return $this->success_response("Berhasil Mengambil Data", $response, $request->all());
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
                'subject_name' => 'required|string',
                'subject_code' =>'required|string',
                'subject_id' => 'required|numeric',
            ]);

            $subject_model = MsubjectsModel::select('m_subjects.*')->where('m_subjects.subject_id', $request->subject_id)->first();
            if(!$subject_model){
                throw new \Exception("Data Section Tidak Ditemukan");
            }
            $subject_model->subject_name =  $request->subject_name;
            $subject_model->subject_code =  $request->subject_code;
            if(!$subject_model->save()){
                throw new \Exception("Gagal Menyimpan Data Ruangan");
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
                'subject_id' => 'required|numeric',
                'subject_status' => 'required|numeric|in:0,1',
            ]);

            $subject_model = MsubjectsModel::select('m_subjects.*')->where('m_subjects.subject_id', $request->subject_id)->first();

            if(!$subject_model){
                throw new \Exception("Data Subject Tidak Ditemukan");
            }

            $subject_model->subject_status = $request->subject_status;
            if(!$subject_model->save()){
                throw new \Exception("Gagal Menyimpan Data Subject");
            }
            $response = [
                'detail_section'=>$subject_model,
            ];
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }
}