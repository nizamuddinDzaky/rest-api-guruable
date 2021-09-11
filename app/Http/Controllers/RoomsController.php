<?php

namespace App\Http\Controllers;

// use Laravel\Lumen\Routing\Controller ;

use App\Models\MRoomsModel;
use App\Models\MSectionsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Yajra\DataTables\DataTablesServiceProvider;
use DataTables;
class RoomsController extends Controller
{
    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'room_name' => 'required|string',
                'room_code' =>'required|string|unique:m_rooms',
            ]);

            $room_model = new MRoomsModel;
            $room_model->room_name =  $request->room_name;
            $room_model->room_code =  $request->room_code;
            $room_model->room_status = $request->room_status ?? 1;

            if(!$room_model->save()){
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

    public function list(Request $request)
    {
        DB::beginTransaction();
        try {
            $search = $request->search;
            $limit = $request->limit;
            $offset = $request->offset;
            $status = $request->section_status ;

            $query = MRoomsModel::select('m_rooms.*');
            if($status != null) {
                $query = $query->where('room_status', $status);
            }
            $record_total = $query->count();
            
            if($search){
                $query = $query->where('room_name', 'LIKE', "%{$search}%") 
                                ->orWhere('room_code', 'LIKE', "%{$search}%");
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
                'room_id' => 'required|numeric',
            ]);

            $room = MRoomsModel::select('m_rooms.*')->where('m_rooms.room_id', $request->room_id)->first();
            if(!$room){
                throw new \Exception("Data Room Tidak Ditemukan");
            }
            $response = [
                'detail_room'=>$room,
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
                'room_name' => 'required|string',
                'room_code' =>'required|string',
                'room_id' => 'required|numeric',
            ]);

            $room_model = MRoomsModel::select('m_rooms.*')->where('m_rooms.room_id', $request->room_id)->first();
            if(!$room_model){
                throw new \Exception("Data Section Tidak Ditemukan");
            }
            $room_model->room_name =  $request->room_name;
            $room_model->room_code =  $request->room_code;
            if(!$room_model->save()){
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
                'room_id' => 'required|numeric',
                'room_status' => 'required|numeric|in:0,1',
            ]);

            $room_model = MRoomsModel::select('m_rooms.*')->where('m_rooms.room_id', $request->room_id)->first();

            if(!$room_model){
                throw new \Exception("Data Ruangan Tidak Ditemukan");
            }

            $room_model->room_status = $request->room_status;
            if(!$room_model->save()){
                throw new \Exception("Gagal Menyimpan Data Ruangan");
            }
            $response = [
                'detail_section'=>$room_model,
            ];
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }
}