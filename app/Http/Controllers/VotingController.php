<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class VotingController extends Controller 
{
    public function vote($id, Request $request)
    {
        $aspirasi = Aspirasi::where('status', 'approved')->findOrFail($id);

        $data = $request->validate([
            'nilai' => ['required', 'in:setuju,tidak_setuju'],
        ]);

        try {
            Voting::create([
                'id_aspirasi' => $aspirasi->id_aspirasi,
                'id_user'     => auth()->id(),
                'nilai'       => $data['nilai'],
            ]);
        } catch (QueryException $e) {
            // melanggar unique (satu akun satu suara)
            return back()->with('error', 'Anda sudah memberikan suara untuk aspirasi ini.');
        }

        return back()->with('success', 'Terima kasih, suara Anda telah tercatat.');
    }
}
