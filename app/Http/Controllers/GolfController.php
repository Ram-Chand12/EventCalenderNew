<?php

namespace App\Http\Controllers;

use App\Models\golf_group;
use App\Rules\ImageSize;
use App\Rules\ImageType;
use Exception;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GolfController extends Controller
{
    public function index()
    {
        $golf_group = golf_group::orderBy('id', 'desc')->paginate(10);
        return view('golf_group.golf', compact('golf_group'));
    }

    public function create()
    {
        return view('golf_group.golf-add');
    }


    public function store(Request $request)
    {

        $request->validate([

            'name' => 'required|string|max:255',
            // 'image' => ['required', new ImageType],

        ]);
        if ($request->hasFile('image')) {
            $destinationPath = 'group_images';
            $myimage = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path($destinationPath), $myimage);
        } else {

            $myimage = null;
        }

        $status = $request->has('status') ? true : false;

        golf_group::create([
            'gname' => $request->input('name'),
            'image' => $myimage ? $destinationPath . '/' . $myimage : null,
            'status' => $status
        ]);

        Alert::success('Success', 'Golf Group has been saved !');
        return redirect()->route('golf-group');
    }

    public function edit($id)
    {

        $golf_group = golf_group::findOrFail($id);


        return view('golf_group.golf-edit', [
            'golf_group' => $golf_group,
        ]);

    }
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // 'image' => [ new ImageType],
        ]);

        $golf_group = golf_group::find($request->id);
        // dd($request->hasFile('image'));

        if ($request->hasFile('image')) {
            // dd('sfdf');
            $destinationPath = 'group_images';
            $myimage = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path($destinationPath), $myimage);


            if (File::exists(public_path($golf_group->image))) {
                File::delete(public_path($golf_group->image));
            }


            $golf_group->image = $destinationPath . '/' . $myimage;
        }


        $golf_group->gname = $validatedData['name'];


        $status = $request->has('status') ? true : false;
        $golf_group->status = $status;


        $golf_group->save();

        Alert::success('Success', 'Golf Group has been updated!');
        return redirect()->route('golf-group');
    }

    public function destroy($id)
    {
        try {
            $deletedgolf_group = golf_group::findOrFail($id);

            $deletedgolf_group->delete();

            Alert::success('Success', 'Golf Group has been deleted !');
            return redirect()->route('golf-group');
        } catch (Exception $ex) {
            Alert::warning('Error', 'Cant deleted, Golf Group already used !');
            return redirect()->route('golf-group');
        }
    }
}


