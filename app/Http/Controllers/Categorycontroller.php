<?php

namespace App\Http\Controllers;

use App\Models\category;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use App\Models\wordpress_reference;
use App\Jobs\wordpressSyncerJob;

class Categorycontroller extends Controller
{
    public function index()
    {
        $category = category::orderby('id', 'desc')->paginate(10);
        return view('category.category', compact('category'));
    }


    public function create()
    {
        $categories = category::where('status',true)->get();
        $categoriesData = $this->buildCategoryHierarchy($categories);
        return view('category.category-add', compact('categoriesData'));
    }

    function buildCategoryHierarchy($categories, $parentId = null, $prefix = '')
    {
        $result = [];
        foreach ($categories as $category) {
            if ($category->parent == $parentId) {
                $name = $prefix . $category->name;
                $result[$category->id] = $name;
                $children = $this->buildCategoryHierarchy($categories, $category->id, $name . ' > ');
                $result = $result + $children;
            }
        }
        return $result;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent' => 'nullable|string|max:255',
        ]);

        $status = $request->has('status') ? true : false;
        category::create([
            'name' => $data['name'],
            'parent' => $data['parent'],
            'status' => $status
        ]);
        wordpressSyncerJob::dispatch();

        Alert::success('Success', 'Category has been saved !');
        return redirect('/category');
    }

    public function edit($id)
    {
        $categories = category::where('status', true)->get();
        $category = category::find($id);

        $categoriesData = $this->buildCategoryHierarchy($categories);

        return view('category.category-edit', compact('category', 'categoriesData'));
    }

    public function update(request $request)
    {

        $data = $request->all();

        $status = $request->has('status') ? true : false;

        category::where('id', $data['id'])->update([
            'name' => $data['name'],
            'parent' => $data['parent'],
            'status' => $status

        ]);

        wordpress_reference::updateWithoutTimestamp(
            [
                'status' => 1,
                'no_of_tries' => 1,
            ],
            [
                'ref_id' => $request->id,
                'entity_type' => 'category'
            ]
        );
        
        wordpressSyncerJob::dispatch();
        Alert::success('Success', 'Category has been updated!');
        return redirect()->route('category');

    }

    public function destroy($id)
    {
        try {
            $category = category::findOrFail($id);

            $category->delete();
            wordpressSyncerJob::dispatch();
            Alert::success('Success', 'Category has been deleted !');
        return redirect()->route('category');
        } catch (Exception $ex) {
            Alert::warning('Error', 'Cant deleted, Category already used !');
        return redirect()->route('category');
        }
    }

}
