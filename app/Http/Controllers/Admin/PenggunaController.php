<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JabatanKomisi;
use App\Models\Komisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator')]
class PenggunaController extends Controller
{
    public function index()
    {
        return view('admin.pengguna.index', [
            'komisiList' => Komisi::orderBy('nama')->get(),
            'jabatanList' => JabatanKomisi::orderBy('nama')->get(),
        ]);
    }

    public function data()
    {
        return DataTables::of(User::with(['komisi', 'jabatanKomisi']))
            ->addColumn('komisi_nama', fn ($u) => $u->komisi?->nama ?? '—')
            ->addColumn('jabatan_nama', fn ($u) => $u->jabatanKomisi?->nama ?? '—')
            ->addColumn('status_badge', fn ($u) => $u->aktif
                ? '<span class="badge text-bg-success">Aktif</span>'
                : '<span class="badge text-bg-secondary">Nonaktif</span>')
            ->addColumn('aksi', function ($u) {
                $updateUrl = route('admin.pengguna.update', $u->id);
                $resetUrl = route('admin.pengguna.reset-password', $u->id);
                $destroyUrl = route('admin.pengguna.destroy', $u->id);

                $btns = '<button type="button" class="btn btn-sm btn-outline-primary btn-edit-pengguna" '
                    .'data-url="'.$updateUrl.'" data-name="'.e($u->name).'" data-email="'.e($u->email).'" '
                    .'data-username="'.e($u->username).'" data-role="'.$u->role.'" data-komisi_id="'.$u->komisi_id.'" '
                    .'data-jabatan_komisi_id="'.$u->jabatan_komisi_id.'" data-aktif="'.($u->aktif ? 1 : 0).'">Ubah</button> '
                    .'<button type="button" class="btn btn-sm btn-outline-warning btn-reset-pengguna" data-url="'.$resetUrl.'" data-name="'.e($u->name).'">Reset Sandi</button> ';

                if ($u->id !== Auth::id()) {
                    $btns .= '<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-pengguna" data-url="'.$destroyUrl.'" data-name="'.e($u->name).'">Hapus</button>';
                }

                return $btns;
            })
            ->rawColumns(['status_badge', 'aksi'])
            ->toJson();
    }

    private function rules(?string $id = null): array
    {
        $emailUnique = $id ? "unique:users,email,{$id}" : 'unique:users,email';
        $usernameUnique = $id ? "unique:users,username,{$id}" : 'unique:users,username';

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', $emailUnique],
            'username' => ['nullable', 'string', 'max:100', $usernameUnique],
            'role' => ['required', 'in:administrator,verifikator,pimpinan,komisi'],
            'komisi_id' => ['required_if:role,komisi', 'nullable', 'uuid', 'exists:komisi,id'],
            'jabatan_komisi_id' => ['required_if:role,komisi', 'nullable', 'uuid', 'exists:jabatan_komisi,id'],
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate([...$this->rules(), 'password' => ['required', 'string', 'min:8']]);
        $data['password'] = Hash::make($data['password']);
        $data['aktif'] = $request->boolean('aktif');

        if ($data['role'] !== 'komisi') {
            $data['komisi_id'] = null;
            $data['jabatan_komisi_id'] = null;
        }

        User::create($data);

        return response()->json(['message' => 'Pengguna berhasil ditambahkan.']);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate($this->rules($id));
        $data['aktif'] = $request->boolean('aktif');

        if ($data['role'] !== 'komisi') {
            $data['komisi_id'] = null;
            $data['jabatan_komisi_id'] = null;
        }

        $user->update($data);

        return response()->json(['message' => 'Pengguna berhasil diperbarui.']);
    }

    public function resetPassword(string $id)
    {
        $user = User::findOrFail($id);
        $newPassword = Str::password(12);
        $user->update(['password' => Hash::make($newPassword), 'login_attempts' => 0]);

        return response()->json([
            'message' => "Sandi baru untuk {$user->name}: {$newPassword} (catat sekarang, tidak ditampilkan lagi)",
        ]);
    }

    public function destroy(string $id)
    {
        if ($id === Auth::id()) {
            return response()->json(['message' => 'Tidak bisa menghapus akun sendiri.'], 422);
        }

        User::findOrFail($id)->delete();

        return response()->json(['message' => 'Pengguna berhasil dihapus.']);
    }
}
