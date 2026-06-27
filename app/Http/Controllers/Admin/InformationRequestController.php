<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InformationRequest;
use Illuminate\Http\Request;

class InformationRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $requests = InformationRequest::with('user')
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.information-requests.index', compact('requests', 'status'));
    }

    public function show(InformationRequest $informationRequest)
    {
        return view('admin.information-requests.show', compact('informationRequest'));
    }

    public function respond(Request $request, InformationRequest $informationRequest)
    {
        $request->validate(['response' => 'required|string']);

        $informationRequest->update([
            'response'      => $request->input('response'),
            'responded_by'  => auth()->id(),
            'responded_at'  => now(),
            'status'        => 'fulfilled',
        ]);

        return redirect()->back()->with('success', 'Respon berhasil dikirim.');
    }

    public function destroy(InformationRequest $informationRequest)
    {
        $informationRequest->delete();
        return redirect()->route('admin.information-requests.index')->with('success', 'Permintaan berhasil dihapus.');
    }
}
