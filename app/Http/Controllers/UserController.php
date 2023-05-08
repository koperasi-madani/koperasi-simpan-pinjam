<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::with('roles')->get();
        return view('pages.akun.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.akun.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required',
        ]);
        try {
            $user = new User;
            $user->name = $request->get('nama');
            $user->username = $request->get('username');
            $user->email = $request->get('email');
            $user->password = Hash::make('password');

            $current_user = User::role($request->get('role'))->get();
            $noAkun = null;

            if($current_user->count() > 0) {
                $noAkun = $current_user[0]->kode_user;
                $lastIncrement = substr($noAkun, 4);

                $noAkun = str_pad($lastIncrement + 1, 3, 0, STR_PAD_LEFT);
                $noAkun = $this->getRole($request->get('role')).$noAkun;
            }
            else {
                $noAkun = $this->getRole($request->get('role'))."001";
            }
            $user->kode_user = $noAkun;

            $user->save();
            $user->assignRole($request->get('role'));

            return redirect()->route('akun.index')->withStatus('Berhasil menambahkan data');
        } catch (Exception $e) {
            return redirect()->route('akun.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('akun.index')->withError('Terjadi kesalahan.');
        }
    }
    public function getRole($params)
    {
        $role = ['MA','AD','CS','HT','TL','AK'];
        if ($params == 'manager') {
            return $role[0];
        }elseif ($params == 'admin-kredit') {
            return $role[1];
        }elseif ($params == 'customer-service') {
            return $role[2];
        }elseif ($params == 'head-teller') {
            return $role[3];
        }elseif ($params == 'teller') {
            return $role[4];
        }elseif ($params == 'akuntansi') {
            return $role[5];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::find($id);
        return view('pages.akun.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'confirmed',
        ]);
        try {
            if ($request->has('password') && $request->get('password') != null)  {
                User::where('id',$id)->update([
                    'name' => $request->get('nama'),
                    'username' => $request->get('username'),
                    'email' => $request->get('email'),
                    'password' => Hash::make($request->get('password')),
                ]);
                return redirect()->route('admin.index')->withStatus('Berhasil mengganti password.');
            }
            $user = User::find($id);
            $user->name = $request->get('nama');
            $user->username = $request->get('username');
            $user->email = $request->get('email');
            $user->password = Hash::make('password');
            $user->update();
            if ($request->has('role') && $request->get('role') != null)  {
                DB::table('model_has_roles')->where('model_id',$id)->delete();
                $user->assignRole($request->input('role'));
                return redirect()->route('akun.index')->withStatus('Berhasil mengganti hak akses data');


            }

            return redirect()->route('akun.index')->withStatus('Berhasil mengganti data');

        } catch (Exception $e) {
            return redirect()->route('akun.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('akun.index')->withError('Terjadi kesalahan.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            User::find($id)->delete();

            DB::table('model_has_roles')->where('model_id',$id)->delete();
            return redirect()->route('akun.index')->withStatus('Berhasil menghapus data');
        } catch (Exception $e) {
            return redirect()->route('akun.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('akun.index')->withError('Terjadi kesalahan.');
        }
    }
}
