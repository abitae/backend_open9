<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Certificate;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use App\Models\Instructor;
use App\Models\MediaFile;
use App\Models\NewsletterSubscriber;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\UniqueCodeService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Open9DemoSeeder extends Seeder
{
    public function run(): void
    {
        $codes = app(UniqueCodeService::class);

        $users = $this->seedUsers();
        $instructors = $this->seedInstructors($users);
        $this->seedProjects();
        $posts = $this->seedBlog($users['editor']);
        $courses = $this->seedCourses($instructors);
        $enrollments = $this->seedEnrollments($courses, $users['student'], $codes);

        $this->seedPayments($enrollments, $users['admin'], $codes);
        $this->seedCertificates($enrollments, $codes);
        $this->seedTestimonials();
        $this->seedContacts($users['admin']);
        $this->seedNewsletter();
        $this->seedMedia($users['admin']);
        $this->seedAuditLogs($users['admin'], $courses->first());

        $posts->each(fn (BlogPost $post): array => $post->tags()->sync(BlogTag::query()->inRandomOrder()->limit(2)->pluck('id')->all()));
    }

    /**
     * @return array<string, User>
     */
    private function seedUsers(): array
    {
        $admin = User::query()->where('email', 'admin@open9.dev')->firstOrFail();

        $editor = User::query()->updateOrCreate(
            ['email' => 'editor@open9.dev'],
            ['name' => 'Editor Open9', 'password' => Hash::make('password'), 'status' => 'active', 'email_verified_at' => now()]
        );
        $editor->assignRole('editor');

        $instructor = User::query()->updateOrCreate(
            ['email' => 'docente@open9.dev'],
            ['name' => 'Docente Open9', 'password' => Hash::make('password'), 'status' => 'active', 'email_verified_at' => now()]
        );
        $instructor->assignRole('instructor');

        $student = User::query()->updateOrCreate(
            ['email' => 'estudiante@open9.dev'],
            ['name' => 'Estudiante Open9', 'password' => Hash::make('password'), 'status' => 'active', 'email_verified_at' => now()]
        );
        $student->assignRole('student');

        collect([$admin, $editor, $instructor, $student])->each(function (User $user): void {
            UserProfile::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'document_type' => 'DNI',
                    'document_number' => fake()->unique()->numerify('########'),
                    'city' => 'Lima',
                    'country' => 'Peru',
                    'bio' => 'Perfil demo para operaciones Open9.',
                    'profession' => 'Tecnologia',
                    'company' => 'Open9',
                    'social_links' => ['linkedin' => 'https://linkedin.com/company/open9'],
                ]
            );
        });

        return compact('admin', 'editor', 'instructor', 'student');
    }

    /**
     * @param  array<string, User>  $users
     * @return Collection<int, Instructor>
     */
    private function seedInstructors(array $users)
    {
        return collect([
            [
                'user_id' => $users['instructor']->id,
                'name' => 'Docente Open9',
                'email' => 'docente@open9.dev',
                'profession' => 'Laravel Architect',
            ],
            [
                'user_id' => null,
                'name' => 'Mariana Cloud',
                'email' => 'mariana.cloud@open9.dev',
                'profession' => 'Cloud Engineer',
            ],
            [
                'user_id' => null,
                'name' => 'Diego Data',
                'email' => 'diego.data@open9.dev',
                'profession' => 'Data Specialist',
            ],
        ])->map(fn (array $data): Instructor => Instructor::query()->updateOrCreate(
            ['email' => $data['email']],
            $data + [
                'phone' => '+51 999 000 000',
                'bio' => 'Instructor demo con experiencia en proyectos tecnologicos.',
                'experience' => 'Mas de 8 anos liderando equipos y entrenamientos.',
                'social_links' => ['website' => 'https://open9.dev'],
                'status' => 'active',
            ]
        ));
    }

    /**
     * @return Collection<int, Project>
     */
    private function seedProjects()
    {
        $category = ProjectCategory::query()->firstOrFail();

        return collect([
            ['title' => 'ERP Academico Open9', 'client_name' => 'Open9 Labs', 'is_featured' => true],
            ['title' => 'Ecommerce B2B Laravel', 'client_name' => 'Andes Tech', 'is_featured' => true],
            ['title' => 'Portal de Analitica Cloud', 'client_name' => 'Data Norte', 'is_featured' => false],
            ['title' => 'Automatizacion de Ventas', 'client_name' => 'Ventas Pro', 'is_featured' => false],
        ])->map(fn (array $data, int $index): Project => Project::query()->updateOrCreate(
            ['slug' => Str::slug($data['title'])],
            [
                'project_category_id' => $category->id,
                'title' => $data['title'],
                'short_description' => 'Proyecto demo de portafolio Open9.',
                'description' => 'Caso de estudio con arquitectura Laravel, Livewire y servicios cloud.',
                'client_name' => $data['client_name'],
                'technology_stack' => ['Laravel', 'Livewire', 'PostgreSQL', 'Tailwind CSS'],
                'gallery' => [],
                'project_url' => 'https://open9.dev/proyectos/'.Str::slug($data['title']),
                'github_url' => 'https://github.com/open9/demo',
                'start_date' => now()->subMonths(8 - $index),
                'end_date' => now()->subMonths(6 - $index),
                'status' => 'published',
                'is_featured' => $data['is_featured'],
                'views_count' => 120 + ($index * 35),
                'seo_title' => $data['title'],
                'seo_description' => 'Proyecto destacado Open9.',
                'seo_keywords' => 'laravel, tecnologia, open9',
                'published_at' => now()->subMonths(5 - $index),
            ]
        ));
    }

    /**
     * @return Collection<int, BlogPost>
     */
    private function seedBlog(User $editor)
    {
        $category = BlogCategory::query()->firstOrFail();

        collect(['Laravel', 'Livewire', 'Cloud', 'Data', 'Carrera Tech'])->each(
            fn (string $name): BlogTag => BlogTag::query()->updateOrCreate(['slug' => Str::slug($name)], ['name' => $name])
        );

        return collect([
            'Como construir dashboards administrativos compactos',
            'Buenas practicas con Livewire y FluxUI',
            'PostgreSQL para plataformas educativas',
            'Automatizacion de pagos y certificados',
            'Arquitectura modular en Laravel',
        ])->map(fn (string $title, int $index): BlogPost => BlogPost::query()->updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'user_id' => $editor->id,
                'blog_category_id' => $category->id,
                'title' => $title,
                'excerpt' => 'Articulo demo para el blog Open9.',
                'content' => 'Contenido demo del articulo con enfoque practico para equipos tecnologicos.',
                'status' => $index === 4 ? 'draft' : 'published',
                'is_featured' => $index < 2,
                'views_count' => 500 - ($index * 70),
                'reading_time' => 6 + $index,
                'seo_title' => $title,
                'seo_description' => 'Articulo Open9 sobre tecnologia y educacion.',
                'seo_keywords' => 'open9, laravel, cursos',
                'published_at' => $index === 4 ? null : now()->subWeeks($index + 1),
            ]
        ));
    }

    /**
     * @param  Collection<int, Instructor>  $instructors
     * @return Collection<int, Course>
     */
    private function seedCourses($instructors)
    {
        $category = CourseCategory::query()->firstOrFail();

        return collect([
            ['title' => 'Laravel Administrativo Profesional', 'modality' => 'virtual', 'level' => 'intermedio', 'price' => 690],
            ['title' => 'Livewire y FluxUI para Backoffice', 'modality' => 'virtual', 'level' => 'avanzado', 'price' => 790],
            ['title' => 'PostgreSQL para Aplicaciones Web', 'modality' => 'grabado', 'level' => 'basico', 'price' => 390],
            ['title' => 'Cloud Deploy con Laravel', 'modality' => 'hibrido', 'level' => 'intermedio', 'price' => 890],
        ])->map(function (array $data, int $index) use ($category, $instructors): Course {
            $course = Course::query()->updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'course_category_id' => $category->id,
                    'instructor_id' => $instructors[$index % $instructors->count()]->id,
                    'title' => $data['title'],
                    'subtitle' => 'Curso demo Open9',
                    'description' => 'Programa practico con proyectos reales y enfoque administrativo.',
                    'objectives' => ['Construir modulos administrativos', 'Aplicar seguridad por roles', 'Publicar en produccion'],
                    'requirements' => ['PHP basico', 'Laravel basico'],
                    'target_audience' => ['Desarrolladores', 'Equipos de tecnologia'],
                    'modality' => $data['modality'],
                    'level' => $data['level'],
                    'duration_hours' => 18 + ($index * 6),
                    'start_date' => now()->addWeeks($index + 1),
                    'end_date' => now()->addWeeks($index + 4),
                    'schedule' => 'Martes y jueves 19:00',
                    'price' => $data['price'],
                    'discount_price' => $data['price'] - 100,
                    'currency' => 'PEN',
                    'capacity' => 30,
                    'enrolled_count' => 4 + $index,
                    'image' => 'courses/'.Str::slug($data['title']).'/cover.jpg',
                    'promotional_video_url' => 'https://open9.dev/videos/'.Str::slug($data['title']),
                    'syllabus_file' => 'courses/'.Str::slug($data['title']).'/temario.pdf',
                    'certificate_available' => true,
                    'status' => $index === 3 ? 'draft' : 'published',
                    'is_featured' => $index < 2,
                    'seo_title' => $data['title'],
                    'seo_description' => 'Curso Open9.',
                    'seo_keywords' => 'curso, tecnologia, open9',
                    'published_at' => $index === 3 ? null : now()->subDays($index + 1),
                ]
            );

            $this->seedCourseStructure($course);

            return $course;
        });
    }

    private function seedCourseStructure(Course $course): void
    {
        collect(['Fundamentos', 'Construccion', 'Publicacion'])->each(function (string $moduleTitle, int $moduleIndex) use ($course): void {
            CourseModule::query()->updateOrCreate(
                ['course_id' => $course->id, 'title' => $moduleTitle],
                ['description' => 'Modulo demo del curso.', 'sort_order' => $moduleIndex + 1, 'status' => 'active']
            );
        });

        collect(['Introduccion', 'Practica guiada', 'Reto aplicado', 'Proyecto final'])->each(
            fn (string $lessonTitle, int $lessonIndex): CourseLesson => CourseLesson::query()->updateOrCreate(
                ['course_id' => $course->id, 'title' => $lessonTitle],
                [
                    'description' => 'Leccion demo.',
                    'image' => 'courses/'.Str::slug($course->title)."/lesson-{$lessonIndex}/cover.jpg",
                    'video_url' => 'https://open9.dev/videos/'.Str::slug($course->title).'-'.$lessonIndex,
                    'content' => 'Contenido demo de la leccion.',
                    'resources' => [
                        'courses/'.Str::slug($course->title)."/lesson-{$lessonIndex}/slides.pdf",
                        'courses/'.Str::slug($course->title)."/lesson-{$lessonIndex}/files.zip",
                    ],
                    'duration_minutes' => 20 + ($lessonIndex * 10),
                    'sort_order' => $lessonIndex + 1,
                    'is_preview' => $lessonIndex === 0,
                    'status' => 'active',
                ]
            )
        );
    }

    /**
     * @param  Collection<int, Course>  $courses
     * @return Collection<int, CourseEnrollment>
     */
    private function seedEnrollments($courses, User $student, UniqueCodeService $codes)
    {
        return $courses->flatMap(function (Course $course, int $courseIndex) use ($student, $codes) {
            return collect(range(1, 3))->map(function (int $index) use ($course, $courseIndex, $student, $codes): CourseEnrollment {
                $status = ['pending', 'confirmed', 'completed'][$index - 1];
                $paymentStatus = ['unpaid', 'paid', 'paid'][$index - 1];

                return CourseEnrollment::query()->create([
                    'course_id' => $course->id,
                    'user_id' => $index === 1 ? $student->id : null,
                    'full_name' => "Alumno Demo {$courseIndex}{$index}",
                    'document_type' => 'DNI',
                    'document_number' => fake()->unique()->numerify('########'),
                    'email' => "alumno{$courseIndex}{$index}@open9.dev",
                    'phone' => '+51 988 000 000',
                    'city' => 'Lima',
                    'occupation' => 'Developer',
                    'company' => 'Open9 Demo',
                    'enrollment_code' => $codes->make(CourseEnrollment::class, 'enrollment_code', 'ENR'),
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'amount' => $course->discount_price ?? $course->price,
                    'notes' => 'Inscripcion demo.',
                    'registered_at' => now()->subMonths(5 - $courseIndex)->addDays($index),
                    'confirmed_at' => $status === 'pending' ? null : now()->subMonths(5 - $courseIndex)->addDays($index + 1),
                    'created_at' => now()->subMonths(5 - $courseIndex)->addDays($index),
                    'updated_at' => now()->subMonths(5 - $courseIndex)->addDays($index),
                ]);
            });
        });
    }

    /**
     * @param  Collection<int, CourseEnrollment>  $enrollments
     */
    private function seedPayments($enrollments, User $admin, UniqueCodeService $codes): void
    {
        $enrollments->each(function (CourseEnrollment $enrollment, int $index) use ($admin, $codes): void {
            $status = match ($enrollment->payment_status->value) {
                'paid' => 'approved',
                default => $index % 3 === 0 ? 'rejected' : 'pending',
            };

            Payment::query()->create([
                'course_enrollment_id' => $enrollment->id,
                'user_id' => $enrollment->user_id,
                'payment_code' => $codes->make(Payment::class, 'payment_code', 'PAY'),
                'method' => ['yape', 'plin', 'transferencia', 'tarjeta'][$index % 4],
                'amount' => $enrollment->amount,
                'currency' => 'PEN',
                'transaction_number' => 'TRX'.str_pad((string) ($index + 1), 8, '0', STR_PAD_LEFT),
                'voucher_image' => 'vouchers/demo-'.$index.'.jpg',
                'payment_date' => $enrollment->created_at->toDateString(),
                'status' => $status,
                'reviewed_by' => $status === 'pending' ? null : $admin->id,
                'reviewed_at' => $status === 'pending' ? null : $enrollment->created_at->addDay(),
                'notes' => 'Pago demo.',
                'created_at' => $enrollment->created_at,
                'updated_at' => $enrollment->created_at,
            ]);
        });
    }

    /**
     * @param  Collection<int, CourseEnrollment>  $enrollments
     */
    private function seedCertificates($enrollments, UniqueCodeService $codes): void
    {
        $enrollments
            ->filter(fn (CourseEnrollment $enrollment): bool => $enrollment->status->value === 'completed')
            ->each(fn (CourseEnrollment $enrollment): Certificate => Certificate::query()->create([
                'course_enrollment_id' => $enrollment->id,
                'user_id' => $enrollment->user_id,
                'course_id' => $enrollment->course_id,
                'certificate_code' => $codes->make(Certificate::class, 'certificate_code', 'CERT'),
                'student_name' => $enrollment->full_name,
                'course_name' => $enrollment->course->title,
                'issued_date' => now()->toDateString(),
                'pdf_path' => 'certificates/demo-'.$enrollment->id.'.pdf',
                'verification_url' => url('/certificados/verificar'),
                'status' => 'active',
            ]));
    }

    private function seedTestimonials(): void
    {
        collect([
            ['name' => 'Ana Rivera', 'type' => 'course', 'content' => 'El curso fue directo y aplicable.'],
            ['name' => 'Luis Torres', 'type' => 'project', 'content' => 'El proyecto mejoro nuestro flujo administrativo.'],
            ['name' => 'Carla Mendoza', 'type' => 'general', 'content' => 'Open9 combina claridad tecnica y ejecucion.'],
        ])->each(fn (array $data, int $index): Testimonial => Testimonial::query()->updateOrCreate(
            ['name' => $data['name']],
            $data + ['profession' => 'Tech Lead', 'company' => 'Demo Co', 'rating' => 5, 'status' => 'active', 'sort_order' => $index + 1]
        ));
    }

    private function seedContacts(User $admin): void
    {
        collect(['new', 'read', 'answered', 'archived'])->each(
            fn (string $status, int $index): Contact => Contact::query()->create([
                'name' => "Contacto Demo {$index}",
                'email' => "contacto{$index}@open9.dev",
                'phone' => '+51 977 000 000',
                'subject' => 'Consulta demo',
                'message' => 'Mensaje recibido desde el formulario publico.',
                'source' => 'web',
                'status' => $status,
                'assigned_to' => $status === 'new' ? null : $admin->id,
                'answered_at' => $status === 'answered' ? now() : null,
            ])
        );
    }

    private function seedNewsletter(): void
    {
        collect(range(1, 8))->each(
            fn (int $index): NewsletterSubscriber => NewsletterSubscriber::query()->updateOrCreate(
                ['email' => "suscriptor{$index}@open9.dev"],
                ['name' => "Suscriptor {$index}", 'status' => $index === 8 ? 'unsubscribed' : 'active', 'subscribed_at' => now()->subDays($index), 'unsubscribed_at' => $index === 8 ? now() : null]
            )
        );
    }

    private function seedMedia(User $admin): void
    {
        collect(['courses', 'projects', 'blog', 'vouchers'])->each(
            fn (string $folder, int $index): MediaFile => MediaFile::query()->create([
                'user_id' => $admin->id,
                'file_name' => "{$folder}-demo-{$index}.jpg",
                'original_name' => "{$folder}.jpg",
                'mime_type' => 'image/jpeg',
                'extension' => 'jpg',
                'size' => 1024 * ($index + 1),
                'disk' => 'public',
                'path' => "media/{$folder}/demo-{$index}.jpg",
                'url' => "/storage/media/{$folder}/demo-{$index}.jpg",
                'alt_text' => "Imagen demo {$folder}",
                'description' => 'Archivo demo.',
                'folder' => $folder,
            ])
        );
    }

    private function seedAuditLogs(User $admin, ?Course $course): void
    {
        if (! $course) {
            return;
        }

        collect(['created', 'updated', 'published'])->each(
            fn (string $action): AuditLog => AuditLog::query()->create([
                'user_id' => $admin->id,
                'action' => $action,
                'module' => 'courses',
                'model_type' => Course::class,
                'model_id' => $course->id,
                'old_values' => $action === 'created' ? null : ['status' => 'draft'],
                'new_values' => ['status' => $course->status->value],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Open9DemoSeeder',
            ])
        );
    }
}
