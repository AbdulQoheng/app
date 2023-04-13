<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NoteController extends Controller
{
    public function index(Request $req)
    {
        if ($req->ajax()) {
            $data = Note::with(['user'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="btnEdit" data-id="' . $row->id_note . '" class="badge badge-warning shadow"><i class="fa fa-pencil"></i></a>';
                    $btn = $btn . ' <a href="javascript:void(0)" id="btnDelete" data-id="' . $row->id_note . '#' . $row->note . '" class="badge badge-danger shadow"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('note.note');
    }

    public function save(Request $req)
    {
        $validateData = $req->validate([
            'note' => 'required|max:255',
        ]);

        if (empty($req->id)) {
            $validateData['user_id'] = auth()->user()->id_user;
            $data = Note::create($validateData);
        } else {
            $data = Note::firstWhere('id_note', $req->id)->update($validateData);
        }

        return response()->json($data);
    }

    public function show(Note $note)
    {
        return response()->json($note);
    }

    public function delete(Note $note){
        $note->delete();
        return response()->json($note);
    }
}
