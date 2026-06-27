<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $feedbacks = Feedback::with('user')
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.feedbacks.index', compact('feedbacks', 'status'));
    }

    public function show(Feedback $feedback)
    {
        if ($feedback->status === 'new') {
            $feedback->update(['status' => 'read']);
        }
        return view('admin.feedbacks.show', compact('feedback'));
    }

    public function reply(Request $request, Feedback $feedback)
    {
        $request->validate(['admin_reply' => 'required|string']);

        $feedback->update([
            'admin_reply' => $request->input('admin_reply'),
            'replied_by'  => auth()->id(),
            'replied_at'  => now(),
            'status'      => 'resolved',
        ]);

        return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()->route('admin.feedbacks.index')->with('success', 'Feedback berhasil dihapus.');
    }
}
