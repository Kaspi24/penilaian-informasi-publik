<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandingPageContent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;

class LandingPageController extends Controller
{
    public function index()
    {
        $current_year = date('Y');
        $contents = LandingPageContent::where('year', $current_year)->orderBy('nth_sequence')->get();
        return view('welcome', compact('contents'));
    }

    public function edit()
    {
        $current_year = date('Y');
        $contents = LandingPageContent::where('year', $current_year)->orderBy('nth_sequence')->get();
        return view('manage-content', compact('contents'));
    }

    public function store(StoreContentRequest $request)
    {
        $validated = $request->validated();
        $current_year = date('Y');
        $contents_count = LandingPageContent::where('year', $current_year)->orderBy('nth_sequence')->count();

        $validated["year"] = $current_year;
        $validated["nth_sequence"] = $contents_count+1;
        
        if(isset($request->image)) {
            $storeFile = $request->file('image')->store('Landing-Page-Contents');
            $validated['image'] = $storeFile;
        }

        
        try {
            LandingPageContent::create($validated);
            return redirect()->back()->with([
                'success'       => 'Konten berhasil disimpan!',
                'last_edited'   => $validated["nth_sequence"]
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', 'Konten gagal diperbarui!');
        }
    }

    public function update(UpdateContentRequest $request)
    {
        $validated = $request->validated();
        
        $content = LandingPageContent::find($request->id);
        
        if(!$content) {
            return redirect()->back()->with('warning', 'Konten tidak ditemukan atau mungkin sudah dihapus, mohon muat ulang halaman!');
        }
        
        $validated['image'] = $content->image;
        
        if(isset($request->image)) {
            if($content->image){
                if( Storage::exists($content->image)){
                    Storage::delete($content->image);
                }
            }
            $storeFile = $request->file('image')->store('Landing-Page-Contents');
            $validated['image'] = $storeFile;
        }

        unset($validated['id']);
        
        try {
            $content->update($validated);
            return redirect()->back()->with([
                'success'       => 'Konten \''.$content->name.'\' berhasil diperbarui!',
                'last_edited'   => $content->nth_sequence
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', 'Konten gagal diperbarui!');
        }
    }

    public function updateSequence(Request $request)
    {
        $id_arr = explode(",", $request->sequence);

        for( $i=0; $i<count($id_arr); $i++ )
        {
            $content = LandingPageContent::find($id_arr[$i]);
            $content->update(['nth_sequence' => $i+1]);
            // dd($id_arr[$i]);
        }
        return redirect()->back()->with([
            'success'       => 'Urutan Konten berhasil disimpan!'
        ]);
    }

    public function delete(Request $request)
    {
        $content = LandingPageContent::find($request->id);

        if(!$content) {
            return redirect()->back()->with('warning', 'Konten tidak ditemukan atau mungkin sudah dihapus, mohon muat ulang halaman!');
        }

        if($content->image){
            if( Storage::exists($content->image)){
                Storage::delete($content->image);
            }
        }
        
        $content_name = $content->name;

        try {
            $content->delete();
            $current_year = date('Y');
            $contents = LandingPageContent::where('year', $current_year)->orderBy('nth_sequence')->get();
            foreach ($contents as $index => $content) {
                $content->update(['nth_sequence' => $index+1]);
                $content->save();
            }
            return redirect()->back()->with([
                'success'       => 'Konten \''.$content_name.'\' berhasil dihapus!'
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', 'Konten gagal dihapus!');
        }
        // dd($content);
    }
}
