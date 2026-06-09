<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // ── Public: عرض فورم الحجز ───────────────────────────────────────────────
    public function create()
    {
        return view('tickets.create');
    }

    // ── Public: حفظ التذكرة الجديدة ─────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'company_name'  => 'nullable|string|max:200',
            'phone'         => 'required|string|max:30',
            'address'       => 'nullable|string|max:500',
            'visit_type'    => 'required|in:technical_support,consultation,installation,maintenance,training,other',
            'expected_cost' => 'nullable|numeric|min:0|max:9999999',
            'notes'         => 'nullable|string|max:2000',
        ]);

        $validated['ticket_number'] = Ticket::generateTicketNumber();
        $validated['status']        = 'pending';

        $ticket = Ticket::create($validated);

        return redirect()->route('tickets.success', $ticket->ticket_number);
    }

    // ── Public: صفحة نجاح الحجز ──────────────────────────────────────────────
    public function success(string $number)
    {
        $ticket = Ticket::where('ticket_number', $number)->firstOrFail();
        return view('tickets.success', compact('ticket'));
    }

    // ── Dashboard: قائمة كل التذاكر (للمسجلين) ──────────────────────────────
    public function index(Request $request)
    {
        abort_unless(auth()->check(), 403);

        $query = Ticket::latest();

        // فلتر بالحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلتر بنوع الزيارة
        if ($request->filled('visit_type')) {
            $query->where('visit_type', $request->visit_type);
        }

        // بحث بالاسم أو رقم التذكرة أو الشركة
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn ($s) =>
                $s->where('name', 'like', "%{$q}%")
                  ->orWhere('ticket_number', 'like', "%{$q}%")
                  ->orWhere('company_name', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
            );
        }

        $tickets = $query->paginate(20)->withQueryString();

        // إحصائيات سريعة
        $stats = [
            'total'       => Ticket::count(),
            'pending'     => Ticket::where('status', 'pending')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'completed'   => Ticket::where('status', 'completed')->count(),
        ];

        return view('tickets.index', compact('tickets', 'stats'));
    }

    // ── Dashboard: عرض تذكرة واحدة ───────────────────────────────────────────
    public function show(Ticket $ticket)
    {
        abort_unless(auth()->check(), 403);
        return view('tickets.show', compact('ticket'));
    }

    // ── Dashboard: تحديث حالة التذكرة ────────────────────────────────────────
    public function updateStatus(Request $request, Ticket $ticket)
    {
        abort_unless(auth()->check(), 403);

        $validated = $request->validate([
            'status'       => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'scheduled_at' => 'nullable|date',
            'assigned_to'  => 'nullable|exists:users,id',
            'notes'        => 'nullable|string|max:2000',
        ]);

        $ticket->update($validated);

        return back()->with('success', 'تم تحديث حالة التذكرة بنجاح.');
    }
}
