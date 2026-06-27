<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $consultations = Consultation::with(['user', 'answerer'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.consultations.index', compact('consultations', 'status'));
    }

    public function show(Consultation $consultation)
    {
        return view('admin.consultations.show', compact('consultation'));
    }

    public function answer(Request $request, Consultation $consultation)
    {
        $request->validate(['answer' => 'required|string']);

        $consultation->update([
            'answer'      => $request->input('answer'),
            'answered_by' => auth()->id(),
            'answered_at' => now(),
            'status'      => 'answered',
        ]);

        return redirect()->back()->with('success', 'Jawaban berhasil dikirim.');
    }

    public function destroy(Consultation $consultation)
    {
        $consultation->delete();
        return redirect()->route('admin.consultations.index')->with('success', 'Konsultasi berhasil dihapus.');
    }
}
