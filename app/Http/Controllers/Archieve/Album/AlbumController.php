<?php

namespace App\Http\Controllers\Archieve\Album;

use App\Http\Controllers\Controller;
use App\Models\Archieve\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['albums'] = Album::whereNull('deleted_by')->whereNull('deleted_at')->paginate('1');
        return view('archieve.album.index', $data);
    }

    /**
     * Generate Get Album
     */
    public function getAlbum(Request $request)
    {
        $input = $request->all();

        if (!is_null($input['query'])) {
            $album = Album::whereNull('deleted_by')
                ->whereNull('deleted_at')
                ->where('name', 'like', '%' . $input['query'] . '%')
                ->paginate('6');
        } else {
            $album = Album::whereNull('deleted_by')->whereNull('deleted_at')->paginate('6');
        }

        if (count($album) > 0) {
            return view('archieve.album.partials.list', ['albums' => $album]);
        } else {
            return view('sales_order.partials.notfound');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Request Validation
            $request->validate([
                'name' => 'required',
                'date' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store Album
            $album = Album::lockforUpdate()->create([
                'name' => $request->name,
                'date' => $request->date,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($album) {
                DB::commit();
                return redirect()
                    ->route('archieve.album.index')
                    ->with(['success' => 'Berhasil Menambahkan Album']);
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Tambah Album'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $album = Album::findOrFail($id);
            $data['album'] = $album;
            return view('archieve.album.detail', $data);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource return json.
     */
    public function showJson(string $id)
    {
        try {
            $album = Album::with(['createdBy', 'updatedBy'])->findOrFail($id);
            $album = $album->toArray();
            $album['date_format'] = date('d F Y', strtotime($album['date']));
            $album['updated_by'] = $album['updated_by']['name'];
            $album['updated_at'] = date('d F Y H:i:s', strtotime($album['updated_at']));
            return response()->json($album, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Request Validation
            $request->validate([
                'name' => 'required',
                'date' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store Album
            $album_update = Album::where('id', $id)->update([
                'name' => $request->name,
                'date' => $request->date,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Update Data
            if ($album_update) {
                DB::commit();
                return redirect()
                    ->route('archieve.album.index')
                    ->with(['success' => 'Berhasil Perbarui Album']);
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Perbarui Album'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function uploadImage(Request $request, string $id)
    {
        try {
            // Request Validation
            $request->validate([
                'attachment' => 'required',
            ]);

            DB::beginTransaction();

            $album = Album::find($id);

            // Image Path
            $path = 'public/archieve/album/' . $id;
            $path_store = 'storage/archieve/album/' . $id;

            if (!is_null($album->attachment)) {
                $attachment_collection = json_decode($album->attachment);

                foreach ($request->file('attachment') as $index => $attachment) {
                    $file_name_only = explode('.' . $attachment->getClientOriginalExtension(), $attachment->getClientOriginalName())[0];
                    $file_name = $attachment->getClientOriginalName();

                    // // File Upload Configuration
                    // $exploded_name = explode(' ', strtolower($album->name));
                    // $file_name_config = implode('_', $exploded_name);
                    // $file_name_only = $id . '_' . ($index + 1) . '_' . $file_name_config;
                    // $file_name = $id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

                    // Uploading File
                    $attachment->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        array_push($attachment_collection, [
                            'file_name' => $file_name_only,
                            'attachment' => $path_store . '/' . $file_name,
                        ]);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Foto'])
                            ->withInput();
                    }
                }
            } else {
                // Check Exsisting Path
                if (!Storage::exists($path)) {
                    // Create new Path Directory
                    Storage::makeDirectory($path);
                }

                $attachment_collection = [];

                foreach ($request->file('attachment') as $index => $attachment) {
                    $file_name_only = explode('.' . $attachment->getClientOriginalExtension(), $attachment->getClientOriginalName())[0];
                    $file_name = $attachment->getClientOriginalName();

                    // // File Upload Configuration
                    // $exploded_name = explode(' ', strtolower($album->name));
                    // $file_name_config = implode('_', $exploded_name);
                    // $file_name_only = $id . '_' . ($index + 1) . '_' . $file_name_config;
                    // $file_name = $id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

                    // Uploading File
                    $attachment->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        $attachment_collection[] = [
                            'file_name' => $file_name_only,
                            'attachment' => $path_store . '/' . $file_name,
                        ];
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Foto'])
                            ->withInput();
                    }
                }
            }

            // Update Record for Attachment
            $album_attachment = $album->update([
                'attachment' => $attachment_collection,
            ]);

            // Validation Update Attachment Album Record
            if ($album_attachment) {
                DB::commit();
                return redirect()
                    ->back()
                    ->with(['success' => 'Berhasil Menambahkan Foto']);
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Menambahkan Foto'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function changeImage(Request $request, string $id)
    {
        try {
            // Request Validation
            $request->validate([
                'file_name_form' => 'required',
            ]);

            DB::beginTransaction();

            $album = Album::find($id);

            // Image Path
            $path = 'public/archieve/album/' . $id;
            $path_store = 'storage/archieve/album/' . $id;

            $attachment_collection = json_decode($album->attachment);

            $new_attachment_collection = [];

            foreach ($attachment_collection as $index => $attachment) {
                if ($request->file_name_form[$index] != $attachment->file_name) {
                    $extension = '.' . explode('.', $attachment->attachment)[count(explode('.', $attachment->attachment)) - 1];
                    Storage::move($path . '/' . $attachment->file_name . $extension, $path . '/' . $request->file_name_form[$index] . $extension);

                    if (Storage::exists($path . '/' . $request->file_name_form[$index] . $extension)) {
                        array_push($new_attachment_collection, [
                            'file_name' => $request->file_name_form[$index],
                            'attachment' => $path_store . '/' . $request->file_name_form[$index] . $extension,
                        ]);
                    } else {
                        return response()->json(['failed' => 'Gagal Perbarui Nama File'], 400);
                    }
                } else {
                    array_push($new_attachment_collection, [
                        'file_name' => $attachment->file_name,
                        'attachment' => $attachment->attachment,
                    ]);
                }
            }

            // Update Record for Attachment
            $album_attachment = $album->update([
                'attachment' => $new_attachment_collection,
            ]);

            // Validation Update Attachment Album Record
            if ($album_attachment) {
                DB::commit();
                return response()->json(['success' => 'Berhasil Perbarui Nama File'], 200);
            } else {
                // Failed and Rollback
                DB::rollBack();
                return response()->json(['failed' => 'Gagal Perbarui File'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['failed' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroyImage(Request $request, string $id)
    {
        try {
            // Request Validation
            $request->validate([
                'file_name' => 'required',
            ]);

            DB::beginTransaction();

            $album = Album::find($id);

            // Image Path
            $path = 'public/archieve/album/' . $id;

            $attachment_collection = json_decode($album->attachment);

            $new_attachment_collection = [];

            foreach ($attachment_collection as $index => $attachment) {
                if ($request->file_name != $attachment->file_name) {
                    array_push($new_attachment_collection, [
                        'file_name' => $attachment->file_name,
                        'attachment' => $attachment->attachment,
                    ]);
                } else {
                    $extension = '.' . explode('.', $attachment->attachment)[count(explode('.', $attachment->attachment)) - 1];

                    Storage::delete($path . '/' . $attachment->file_name . $extension);

                    if (Storage::exists($path . '/' . $attachment->file_name . $extension)) {
                        return response()->json(['failed' => 'Gagal Hapus File'], 400);
                    }
                }
            }

            if (empty($new_attachment_collection)) {
                $new_attachment_collection = null;
            }

            // Update Record for Attachment
            $album_attachment = $album->update([
                'attachment' => $new_attachment_collection,
            ]);

            // Validation Update Attachment Album Record
            if ($album_attachment) {
                DB::commit();
                return response()->json(['success' => 'Berhasil Perbarui File'], 200);
            } else {
                // Failed and Rollback
                DB::rollBack();
                return response()->json(['failed' => 'Gagal Perbarui File'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['failed' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            // Destroy with Softdelete
            $album_destroy = Album::where('id', $id)->update(['deleted_by' => Auth::user()->id, 'deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy Album
            if ($album_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Album');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Album');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
