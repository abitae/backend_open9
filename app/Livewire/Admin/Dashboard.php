<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Payment;
use App\Models\Project;
use BackedEnum;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * @return array<string, int|string>
     */
    public function metrics(): array
    {
        return [
            'Cursos publicados' => Course::query()->where('status', 'published')->count(),
            'Inscripciones' => CourseEnrollment::query()->count(),
            'Pagos aprobados' => Payment::query()->where('status', 'approved')->count(),
            'Ingresos' => 'PEN '.number_format((float) Payment::query()->where('status', 'approved')->sum('amount'), 2),
            'Contactos nuevos' => Contact::query()->where('status', 'new')->count(),
            'Proyectos destacados' => Project::query()->where('is_featured', true)->count(),
        ];
    }

    /**
     * @return Collection<int, CourseEnrollment>
     */
    public function latestEnrollments(): Collection
    {
        return CourseEnrollment::query()->latest()->limit(5)->get();
    }

    /**
     * @return Collection<int, Payment>
     */
    public function pendingPayments(): Collection
    {
        return Payment::query()->where('status', 'pending')->latest()->limit(5)->get();
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public function popularPosts(): Collection
    {
        return BlogPost::query()->orderByDesc('views_count')->limit(5)->get();
    }

    /**
     * @return array{labels: list<string>, enrollments: list<int>, payments: list<int>, revenue: list<float>, max: float}
     */
    public function monthlySeries(): array
    {
        $months = collect(range(5, 0))
            ->map(fn (int $monthsAgo): CarbonImmutable => now()->toImmutable()->subMonths($monthsAgo)->startOfMonth());

        $start = $months->first();
        $end = now()->endOfMonth();

        $enrollments = CourseEnrollment::query()
            ->whereBetween('created_at', [$start, $end])
            ->get(['created_at'])
            ->groupBy(fn (CourseEnrollment $enrollment): string => $enrollment->created_at->format('Y-m'));

        $payments = Payment::query()
            ->whereBetween('created_at', [$start, $end])
            ->get(['amount', 'status', 'created_at'])
            ->groupBy(fn (Payment $payment): string => $payment->created_at->format('Y-m'));

        $labels = [];
        $enrollmentCounts = [];
        $approvedPaymentCounts = [];
        $revenue = [];

        foreach ($months as $month) {
            $key = $month->format('Y-m');
            $labels[] = $month->format('M');
            $enrollmentCounts[] = $enrollments->get($key, collect())->count();
            $approved = $payments->get($key, collect())->filter(function (Payment $payment): bool {
                $status = $payment->getAttribute('status');

                return $status instanceof BackedEnum ? $status->value === 'approved' : $status === 'approved';
            });
            $approvedPaymentCounts[] = $approved->count();
            $revenue[] = (float) $approved->sum('amount');
        }

        return [
            'labels' => $labels,
            'enrollments' => $enrollmentCounts,
            'payments' => $approvedPaymentCounts,
            'revenue' => $revenue,
            'max' => max(1, ...$enrollmentCounts, ...$approvedPaymentCounts, ...$revenue),
        ];
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard');
    }
}
