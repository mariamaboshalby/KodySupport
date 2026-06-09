<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use Illuminate\Http\Request;

class AdminTicketTypeController extends Controller
{
    // ── قائمة الأنواع ─────────────────────────────────────────────────────────
    public function index()
    {
        $ticketTypes = TicketType::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.tickets.types', compact('ticketTypes'));
    }

    // ── إضافة نوع جديد ───────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100|unique:ticket_types,name',
            'expected_cost' => 'nullable|numeric|min:0|max:9999999',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        TicketType::create($validated);

        return back()->with('success', 'تم إضافة نوع التذكرة بنجاح.');
    }

    // ── تعديل نوع موجود ──────────────────────────────────────────────────────
    public function update(Request $request, TicketType $ticketType)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100|unique:ticket_types,name,' . $ticketType->id,
            'expected_cost' => 'nullable|numeric|min:0|max:9999999',
            'is_active'     => 'boolean',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $ticketType->update($validated);

        return back()->with('success', 'تم تحديث النوع بنجاح.');
    }

    // ── حذف نوع ──────────────────────────────────────────────────────────────
    public function destroy(TicketType $ticketType)
    {
        // لا نحذف إذا فيه تذاكر مرتبطة
        if ($ticketType->tickets()->exists()) {
            return back()->with('error', 'لا يمكن حذف هذا النوع لأنه مرتبط بتذاكر موجودة.');
        }

        $ticketType->delete();

        return back()->with('success', 'تم حذف النوع بنجاح.');
    }
}
