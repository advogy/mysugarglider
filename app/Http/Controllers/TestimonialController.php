<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Testimonial;
use App\Services\PointService;
use App\Enums\PointType;

class TestimonialController extends Controller
{
    public function adminIndex()
    {
        abort_unless(Auth::user()->is_admin, 403);

        $data = [
            'pending'  => Testimonial::with('user')->where('status', Testimonial::STATUS_PENDING)->latest()->get(),
            'approved' => Testimonial::with('user')->where('status', Testimonial::STATUS_APPROVED)->orderBy('urutan')->get(),
            'rejected' => Testimonial::with('user')->where('status', Testimonial::STATUS_REJECTED)->latest()->get(),
        ];

        return view('testimonials.v_admin_index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'quote' => 'required|string|min:20|max:500',
        ]);

        $user = Auth::user();

        // Satu user hanya boleh punya satu testimonial
        if (Testimonial::where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Anda sudah pernah mengirimkan testimoni.');
        }

        Testimonial::create([
            'user_id' => $user->id,
            'quote'   => $request->quote,
            'author'  => $user->name,
            'durasi'  => null,
            'status'  => Testimonial::STATUS_PENDING,
            'aktif'   => false,
        ]);

        return back()->with('pesan', 'Terima kasih! Testimoni Anda sedang menunggu persetujuan admin.');
    }

    public function approve(Request $request, Testimonial $testimonial)
    {
        abort_unless(Auth::user()->is_admin, 403);

        $request->validate(['durasi' => 'nullable|string|max:50']);

        $testimonial->update([
            'status' => Testimonial::STATUS_APPROVED,
            'aktif'  => true,
            'durasi' => $request->durasi,
            'urutan' => Testimonial::where('status', Testimonial::STATUS_APPROVED)->max('urutan') + 1,
        ]);

        // Beri poin jika user terdaftar dan belum pernah dapat poin ini
        if ($testimonial->user_id) {
            app(PointService::class)->earn(
                $testimonial->user,
                PointType::TESTIMONIAL,
                $testimonial
            );
        }

        return back()->with('pesan', 'Testimoni disetujui dan poin diberikan.');
    }

    public function reject(Testimonial $testimonial)
    {
        abort_unless(Auth::user()->is_admin, 403);

        $testimonial->update([
            'status' => Testimonial::STATUS_REJECTED,
            'aktif'  => false,
        ]);

        return back()->with('pesan', 'Testimoni ditolak.');
    }

    public function destroy(Testimonial $testimonial)
    {
        abort_unless(Auth::user()->is_admin || $testimonial->user_id === Auth::id(), 403);

        // Jika masih pending, user boleh hapus untuk kirim ulang
        if (!Auth::user()->is_admin && !$testimonial->isPending()) {
            return back()->with('error', 'Hanya testimoni yang masih pending yang dapat dihapus.');
        }

        $testimonial->delete();

        return back()->with('pesan', 'Testimoni dihapus.');
    }
}
