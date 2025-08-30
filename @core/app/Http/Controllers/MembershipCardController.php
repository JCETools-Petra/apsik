<?php

namespace App\Http\Controllers;

use App\MembershipCard;
use Illuminate\Http\Request;
use PDF;

class MembershipCardController extends Controller
{
    public function generateCard($id)
    {
        $membership_card = MembershipCard::with('user')->findOrFail($id);
        
        // Asumsi kolom-kolom 'gelar', 'institusi', dan 'nidn' ada di tabel 'users'.
        // Jika tidak, Anda perlu menambahkan kolom ini terlebih dahulu melalui migrasi.

        $pdf = PDF::loadView('card.membership_card', compact('membership_card'))->setPaper('a4', 'landscape');
        return $pdf->download('kartu-anggota-'.$membership_card->card_id.'.pdf');
    }
}