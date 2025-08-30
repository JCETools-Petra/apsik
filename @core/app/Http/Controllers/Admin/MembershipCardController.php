<?php

namespace App\Http\Controllers;

use App\MembershipCard;
use Illuminate\Http\Request;
use PDF; // Pastikan Anda mengimpor Dompdf jika belum

class MembershipCardController extends Controller
{
    public function generateCard($id)
    {
        $membership_card = MembershipCard::with('user')->findOrFail($id);

        $pdf = PDF::loadView('card.membership_card', compact('membership_card'));
        return $pdf->download('kartu-anggota-'.$membership_card->id.'.pdf');
    }
}