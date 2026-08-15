<?php

use App\Models\Course;
use App\Models\CourseEnrollment;

it('renders the enrollment form with the course title', function (): void {
    $course = Course::factory()->create([
        'title' => 'Cloud para empresas',
        'slug' => 'cloud-para-empresas',
    ]);

    $this->get(route('courses.enrollment', $course->slug))
        ->assertOk()
        ->assertSee('Cloud para empresas')
        ->assertSee('Enviar inscripción')
        ->assertSee('OPEN9');
});

it('stores a course enrollment from the public form', function (): void {
    $course = Course::factory()->create();

    $this->post(route('courses.enrollment.store', $course->slug), [
        'full_name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'phone' => '999111222',
        'city' => 'Lima',
    ])
        ->assertRedirect()
        ->assertSessionHas('status');

    $enrollment = CourseEnrollment::query()->where('email', 'ada@example.com')->first();

    expect($enrollment)->not->toBeNull()
        ->and($enrollment->course_id)->toBe($course->id)
        ->and($enrollment->full_name)->toBe('Ada Lovelace');
});

it('renders the certificate verification placeholder', function (): void {
    $this->get(route('certificates.verify', 'CERT-DEMO'))
        ->assertOk()
        ->assertSee('Verificar certificado')
        ->assertSee('OPEN9');
});
