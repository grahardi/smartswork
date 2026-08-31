<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $bulan = $request->input('bulan', now()->format('Y-m'));
        $periode = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $tanggalDipilih = $request->input('tanggal', now()->toDateString());

        // Kumpulkan semua "tanda" per tanggal dalam bulan ini: aksi harian, event manual, deadline project.
        $marks = [];

        $user->dailyActions()
            ->whereYear('tanggal', $periode->year)
            ->whereMonth('tanggal', $periode->month)
            ->get()
            ->each(function ($item) use (&$marks) {
                $marks[$item->tanggal->toDateString()][] = 'aksi';
            });

        $events = $user->calendarEvents()
            ->whereYear('tanggal', $periode->year)
            ->whereMonth('tanggal', $periode->month)
            ->get();
        $events->each(function ($item) use (&$marks) {
            $marks[$item->tanggal->toDateString()][] = 'event';
        });

        $deadlineProjects = Project::whereHas('workplace.users', fn ($q) => $q->where('users.id', $user->id))
            ->whereNotNull('tanggal_selesai')
            ->whereYear('tanggal_selesai', $periode->year)
            ->whereMonth('tanggal_selesai', $periode->month)
            ->get();
        $deadlineProjects->each(function ($item) use (&$marks) {
            $marks[$item->tanggal_selesai->toDateString()][] = 'deadline';
        });

        // Agenda untuk tanggal yang sedang dipilih.
        $tglObj = Carbon::parse($tanggalDipilih);
        $agendaAksi = $user->dailyActions()->with('project.workplace')->whereDate('tanggal', $tglObj)->get();
        $agendaEvents = $user->calendarEvents()->whereDate('tanggal', $tglObj)->get();
        $agendaDeadline = Project::whereHas('workplace.users', fn ($q) => $q->where('users.id', $user->id))
            ->whereDate('tanggal_selesai', $tglObj)
            ->get();

        return view('calendar.index', [
            'periode' => $periode,
            'bulan' => $bulan,
            'marks' => $marks,
            'tanggalDipilih' => $tanggalDipilih,
            'agendaAksi' => $agendaAksi,
            'agendaEvents' => $agendaEvents,
            'agendaDeadline' => $agendaDeadline,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string'],
            'warna' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $request->user()->calendarEvents()->create($validated);

        return redirect()->route('calendar.index', ['tanggal' => $validated['tanggal'], 'bulan' => substr($validated['tanggal'], 0, 7)])
            ->with('status', 'Event berhasil ditambahkan.');
    }

    public function destroy(Request $request, CalendarEvent $event): RedirectResponse
    {
        abort_unless($event->user_id === $request->user()->id, 403);

        $tanggal = $event->tanggal->toDateString();
        $event->delete();

        return redirect()->route('calendar.index', ['tanggal' => $tanggal, 'bulan' => substr($tanggal, 0, 7)])
            ->with('status', 'Event dihapus.');
    }
}
