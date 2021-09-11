<?php

namespace App\Http\Controllers;

// use Laravel\Lumen\Routing\Controller ;

use App\Models\MClassModel;
use App\Models\MSectionsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Yajra\DataTables\DataTablesServiceProvider;
use DataTables;
class SectionController extends Controller
{
    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'section_name' => 'required|string',
                'section_code' =>'required|string|unique:m_sections',
                'section_class_id' =>'required|numeric',
            ]);

            $class_model = MClassModel::where('class_id', $request->section_class_id)->where('class_status', 1)->first();

            if(!$class_model){
                throw new \Exception("Kelas Tidak Ditemukan");
            }

            $section_model = new MSectionsModel;
            $section_model->section_name =  $request->section_name;
            $section_model->section_code =  $request->section_code;
            $section_model->section_class_id =  $request->section_class_id;
            $section_model->section_status = $request->section_status ?? 1;

            if(!$section_model->save()){
                throw new \Exception("Gagal Menyimpan Data Section");
            }

            DB::commit();
            $response = $request->all();
            return $this->success_response("Berhasil Mengambil Data", $response, $request->all());

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
            $status = $request->section_status ;

            $query = MSectionsModel::select('m_sections.*')->with('section_class');
            if($status != null) {
                $query = $query->where('section_status', $status);
            }
            $record_total = $query->count();
            
            if($search){
                $query = $query->where('section_name', 'LIKE', "%{$search}%") 
                                ->orWhere('section_code', 'LIKE', "%{$search}%");
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
                'section_id' => 'required|numeric',
            ]);

            $section = MSectionsModel::select('m_sections.*')->where('m_sections.section_id', $request->section_id)->with('section_class')->first();
            if(!$section){
                throw new \Exception("Data Section Tidak Ditemukan");
            }
            $response = [
                'detail_section'=>$section,
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
                'section_name' => 'required|string',
                'section_code' =>'required|string',
                'section_class_id' =>'required|numeric',
                'section_id' => 'required|numeric',
            ]);

            $section_model = MSectionsModel::select('m_sections.*')->where('m_sections.section_id', $request->section_id)->with('section_class')->first();
            if(!$section_model){
                throw new \Exception("Data Section Tidak Ditemukan");
            }
            $section_model->section_name =  $request->section_name;
            $section_model->section_code =  $request->section_code;
            $section_model->section_class_id =  $request->section_class_id;
            if(!$section_model->save()){
                throw new \Exception("Gagal Menyimpan Data Section");
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
                'section_id' => 'required|numeric',
                'section_status' => 'required|numeric|in:0,1',
            ]);

            $section_model = MSectionsModel::select('m_sections.*')->where('m_sections.section_id', $request->section_id)->with('section_class')->first();

            if(!$section_model){
                throw new \Exception("Data Section Tidak Ditemukan");
            }

            $section_model->section_status = $request->section_status;
            if(!$section_model->save()){
                throw new \Exception("Gagal Menyimpan Data Section");
            }
            $response = [
                'detail_section'=>$section_model,
            ];
            DB::commit();
            return $this->success_response("Berhasil Mengambil Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }
}