<?php

namespace App\Http\Controllers\Archieve\Mail;

use App\Http\Controllers\Controller;
use App\Models\Archieve\IncomingMail;
use App\Models\Master\Classification;
use App\Models\Master\Institution;
use App\Models\Master\TypeMailContent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class IncomingMailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('archieve.mail.incoming-mail.dataTable'); // Route DataTables
        return view('archieve.mail.incoming_mail.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['levels'] = Institution::getLevel();
        $data['classifications'] = Classification::whereNull('deleted_at')->get();
        $data['type_mail_contents'] = TypeMailContent::whereNull('deleted_at')->get();
        return view('archieve.mail.incoming_mail.create', $data);
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable()
    {
        $incoming_mails = IncomingMail::with(['classification', 'typeMailContent', 'institution'])
            ->whereNull('deleted_by')
            ->whereNull('deleted_at')
            ->get();

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($incoming_mails)
            ->addIndexColumn()
            ->addColumn('date', function ($data) {
                return date('d F Y', strtotime($data->date));
            })
            ->addColumn('institution', function ($data) {
                return !is_null($data->institution_id) ? $data->institution->name : 'Eksternal';
            })
            ->addColumn('classification', function ($data) {
                return $data->classification->name;
            })
            ->addColumn('type_mail_content', function ($data) {
                return $data->typeMailContent->name;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('archieve.mail.incoming-mail.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary ms-2 mb-1" title="Detail"><i class="bi bi-eye"></i></a>';

                if (!User::find(Auth::user()->id)->hasRole('kasubdit')) {
                    $btn_action .= '<a href="' . route('archieve.mail.incoming-mail.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ms-2 mb-1" title="Ubah"><i class="bi bi-pencil-square"></i></a>';
                    $btn_action .= '<button class="btn btn-sm btn-danger ms-2 mb-1" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="bi bi-trash"></i></button>';
                }
                $btn_action .= '<a target="_blank" href="' . asset($data->attachment) . '" class="btn btn-sm btn-info ms-2 mb-1" title="Lampiran Dokumen"><i class="bi bi-paperclip"></i></a>';
                return $btn_action;
            })
            ->only(['number', 'date', 'classification', 'institution', 'type_mail_content', 'name', 'action'])
            ->rawColumns(['action'])
            ->make(true);

        return $dataTable;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Request Validation
            $request->validate([
                'number' => 'required',
                'name' => 'required',
                'date' => 'required',
                'classification' => 'required',
                'type_mail_content' => 'required',
                'attachment' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store Incoming Mail
            $incoming_mail = IncomingMail::lockforUpdate()->create([
                'number' => $request->number,
                'classification_id' => $request->classification,
                'type_mail_content_id' => $request->type_mail_content,
                'name' => $request->name,
                'date' => $request->date,
                'institution_id' => $request->institution,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($incoming_mail) {
                // Image Path
                $path = 'public/archieve/mail/incoming-mail';
                $path_store = 'storage/archieve/mail/incoming-mail';

                // Check Exsisting Path
                if (!Storage::exists($path)) {
                    // Create new Path Directory
                    Storage::makeDirectory($path);
                }

                // $attachment_collection = [];

                // foreach ($request->file('attachment') as $index => $attachment) {
                //     // File Upload Configuration
                //     $exploded_name = explode(' ', strtolower($request->name));
                //     $file_name_config = implode('_', $exploded_name);
                //     $file_name = $incoming_mail->id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

                //     // Uploading File
                //     $attachment->storePubliclyAs($path, $file_name);

                //     // Check Upload Success
                //     if (Storage::exists($path . '/' . $file_name)) {
                //         array_push($attachment_collection, $path_store . '/' . $file_name);
                //     } else {
                //         // Failed and Rollback
                //         DB::rollBack();
                //         return redirect()
                //             ->back()
                //             ->with(['failed' => 'Gagal Upload Lampiran Surat Masuk'])
                //             ->withInput();
                //     }
                // }

                // // Update Record for Attachment
                // $incoming_mail_update = IncomingMail::where('id', $incoming_mail->id)->update([
                //     'attachment' => $attachment_collection,
                // ]);

                // // Validation Update Attachment Incoming Mail Record
                // if ($incoming_mail_update) {
                //     DB::commit();
                //     return redirect()
                //         ->route('archieve.mail.incoming-mail.show', ['id' => $incoming_mail->id])
                //         ->with(['success' => 'Berhasil Menambahkan Surat Masuk']);
                // } else {
                //     // Failed and Rollback
                //     DB::rollBack();
                //     return redirect()
                //         ->back()
                //         ->with(['failed' => 'Gagal Update Lampiran Surat Masuk'])
                //         ->withInput();
                // }

                $exploded_name = explode(' ', strtolower($request->name));
                $file_name_config = implode('_', $exploded_name);
                $file = $request->file('attachment');
                $file_name = $incoming_mail->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                // Uploading File
                $file->storePubliclyAs($path, $file_name);

                // Check Upload Success
                if (Storage::exists($path . '/' . $file_name)) {
                    // Update Record for Attachment
                    $incoming_mail_update = IncomingMail::where('id', $incoming_mail->id)->update([
                        'attachment' => $path_store . '/' . $file_name,
                    ]);

                    // Validation Update Attachment Incoming Mail Record
                    if ($incoming_mail_update) {
                        DB::commit();
                        return redirect()
                            ->route('archieve.mail.incoming-mail.show', ['id' => $incoming_mail->id])
                            ->with(['success' => 'Berhasil Menambahkan Surat Masuk']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Update Lampiran Surat Masuk'])
                            ->withInput();
                    }
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Upload Lampiran Surat Masuk'])
                        ->withInput();
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Tambah Surat Masuk'])
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
            $incoming_mail = IncomingMail::with(['classification', 'typeMailContent', 'institution.parent'])->findOrFail($id);
            $data['incoming_mail'] = $incoming_mail;
            return view('archieve.mail.incoming_mail.detail', $data);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $incoming_mail = IncomingMail::with(['classification', 'typeMailContent', 'institution.parent'])->findOrFail($id);
            $data['incoming_mail'] = $incoming_mail;
            $data['levels'] = Institution::getLevel();
            $data['classifications'] = Classification::whereNull('deleted_at')->get();
            $data['type_mail_contents'] = TypeMailContent::whereNull('deleted_at')->get();
            return view('archieve.mail.incoming_mail.edit', $data);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
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
                'number' => 'required',
                'classification' => 'required',
                'type_mail_content' => 'required',
                'name' => 'required',
                'date' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store Incoming Mail
            $incoming_mail_update = IncomingMail::where('id', $id)->update([
                'number' => $request->number,
                'classification_id' => $request->classification,
                'type_mail_content_id' => $request->type_mail_content,
                'name' => $request->name,
                'date' => $request->date,
                'institution_id' => $request->institution,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($incoming_mail_update) {
                // Check Has Request File
                if (!empty($request->allFiles())) {
                    // Get Incoming Mail Record
                    $incoming_mail = IncomingMail::find($id);

                    // Image Path
                    $path = 'public/archieve/mail/incoming-mail';
                    $path_store = 'storage/archieve/mail/incoming-mail';

                    // Check Exsisting Path
                    if (!Storage::exists($path)) {
                        // Create new Path Directory
                        Storage::makeDirectory($path);
                    }

                    /**
                     * Get Filename Attachment Record
                     */
                    $picture_record_exploded = explode('/', $incoming_mail->attachment);
                    $file_name_record = $picture_record_exploded[count($picture_record_exploded) - 1];

                    /**
                     * Remove Has File Exist
                     */
                    if (Storage::exists($path . '/' . $file_name_record)) {
                        Storage::delete($path . '/' . $file_name_record);
                    }

                    $exploded_name = explode(' ', strtolower($request->name));
                    $file_name_config = implode('_', $exploded_name);
                    $file = $request->file('attachment');
                    $file_name = $incoming_mail->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                    // Uploading File
                    $file->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        // Update Record for Attachment
                        $incoming_mail_attachment_update = $incoming_mail->update([
                            'attachment' => $path_store . '/' . $file_name,
                        ]);

                        // Validation Update Attachment Incoming Mail Record
                        if ($incoming_mail_attachment_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.mail.incoming-mail.show', ['id' => $id])
                                ->with(['success' => 'Berhasil Perbarui Surat Masuk']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran Surat Masuk'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Lampiran Surat Masuk'])
                            ->withInput();
                    }

                    // $incoming_mail_attachment = json_decode($incoming_mail->attachment);

                    // foreach ($incoming_mail_attachment as $last_attachment) {
                    //     // File Last Record
                    //     $last_attachment_exploded = explode('/', $last_attachment);
                    //     $file_name_record = $last_attachment_exploded[count($last_attachment_exploded) - 1];

                    //     // Remove Last Record
                    //     if (Storage::exists($path . '/' . $file_name_record)) {
                    //         Storage::delete($path . '/' . $file_name_record);
                    //     }
                    // }

                    // $attachment_collection = [];

                    // foreach ($request->file('attachment') as $index => $attachment) {
                    //     // File Upload Configuration
                    //     $exploded_name = explode(' ', strtolower($request->name));
                    //     $file_name_config = implode('_', $exploded_name);
                    //     $file_name = $incoming_mail->id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

                    //     // Uploading File
                    //     $attachment->storePubliclyAs($path, $file_name);

                    //     // Check Upload Success
                    //     if (Storage::exists($path . '/' . $file_name)) {
                    //         array_push($attachment_collection, $path_store . '/' . $file_name);
                    //     } else {
                    //         // Failed and Rollback
                    //         DB::rollBack();
                    //         return redirect()
                    //             ->back()
                    //             ->with(['failed' => 'Gagal Upload Lampiran Surat Masuk'])
                    //             ->withInput();
                    //     }
                    // }

                    // // Update Record for Attachment
                    // $incoming_mail_attachment_update = $incoming_mail->update([
                    //     'attachment' => $attachment_collection,
                    // ]);

                    // // Validation Update Attachment Incoming Mail Record
                    // if ($incoming_mail_attachment_update) {
                    //     DB::commit();
                    //     return redirect()
                    //         ->route('archieve.mail.incoming-mail.show', ['id' => $id])
                    //         ->with(['success' => 'Berhasil Perbarui Surat Masuk']);
                    // } else {
                    //     // Failed and Rollback
                    //     DB::rollBack();
                    //     return redirect()
                    //         ->back()
                    //         ->with(['failed' => 'Gagal Update Lampiran Surat Masuk'])
                    //         ->withInput();
                    // }
                } else {
                    DB::commit();
                    return redirect()
                        ->route('archieve.mail.incoming-mail.show', ['id' => $id])
                        ->with(['success' => 'Berhasil Perbarui Surat Masuk']);
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Perbarui Surat Masuk'])
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            // Destroy with Softdelete
            $incoming_mail_destroy = IncomingMail::where('id', $id)->update(['deleted_by' => Auth::user()->id, 'deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy Incoming Mail
            if ($incoming_mail_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Surat Masuk');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Surat Masuk');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
