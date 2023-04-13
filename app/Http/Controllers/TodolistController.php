<?php

namespace App\Http\Controllers;

use App\Models\Todolist;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TodolistController extends Controller
{
    public function index(Request $req)
    {
        if ($req->ajax()) {
            $data = Todolist::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="btnEdit" data-id="' . $row->id_todolist . '" class="badge badge-warning shadow"><i class="fa fa-pencil"></i></a>';
                    $btn = $btn . ' <a href="javascript:void(0)" id="btnDelete" data-id="' . $row->id_todolist . '#' . $row->note . '" class="badge badge-danger shadow"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->addColumn('complete', function ($row) {
                    $badge = '';
                    if ($row->complete == 1) {
                        $badge = '<span class="badge badge-success">Selesai</span>';
                    }else{
                        $badge = '<span class="badge badge-danger">Belum Selesai</span>';
                    }

                    return $badge;
                })
                ->rawColumns(['action', 'complete'])
                ->make(true);
        }
        return view('todolist.todolist');
    }

    public function save(Request $req)
    {
        $validateData = $req->validate([
            'note' => 'required|max:255',
            'complete' => 'required|numeric|max:1',
        ]);

        if (empty($req->id)) {
            $validateData['user_id'] = auth()->user()->id_user;
            $data = Todolist::create($validateData);
        } else {
            $data = Todolist::firstWhere('id_todolist', $req->id)->update($validateData);
        }

        return response()->json($data);
    }

    public function show(Todolist $todolist)
    {
        return response()->json($todolist);
    }

    public function delete(Todolist $todolist){
        $todolist->delete();
        return response()->json($todolist);
    }
}
