<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('admin.dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Plataforma')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('Panel') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group
                    :heading="__('Acceso')"
                    expandable
                    :expanded="request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.permissions.*')"
                    class="grid"
                >
                    @can('users.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.users.index')" :current="request()->routeIs('admin.users.*')" wire:navigate>{{ __('Usuarios') }}</flux:sidebar.item>
                    @endcan
                    @can('roles.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.roles.index')" :current="request()->routeIs('admin.roles.*')" wire:navigate>{{ __('Roles') }}</flux:sidebar.item>
                    @endcan
                    @can('permissions.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.permissions.index')" :current="request()->routeIs('admin.permissions.*')" wire:navigate>{{ __('Permisos') }}</flux:sidebar.item>
                    @endcan
                </flux:sidebar.group>

                <flux:sidebar.group
                    :heading="__('Contenido')"
                    expandable
                    :expanded="request()->routeIs('admin.projects.*', 'admin.project-categories.*', 'admin.blog.*', 'admin.blog-categories.*', 'admin.blog-tags.*', 'admin.testimonials.*')"
                    class="grid"
                >
                    @can('projects.view')
                        <flux:sidebar.item icon="folder-git-2" :href="route('admin.projects.index')" :current="request()->routeIs('admin.projects.*')" wire:navigate>{{ __('Proyectos') }}</flux:sidebar.item>
                    @endcan
                    @can('project-categories.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.project-categories.index')" :current="request()->routeIs('admin.project-categories.*')" wire:navigate>{{ __('Categorías de proyectos') }}</flux:sidebar.item>
                    @endcan
                    @can('blog.view')
                        <flux:sidebar.item icon="book-open-text" :href="route('admin.blog.index')" :current="request()->routeIs('admin.blog.*')" wire:navigate>{{ __('Blog') }}</flux:sidebar.item>
                    @endcan
                    @can('blog-categories.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.blog-categories.index')" :current="request()->routeIs('admin.blog-categories.*')" wire:navigate>{{ __('Categorías del blog') }}</flux:sidebar.item>
                    @endcan
                    @can('blog-tags.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.blog-tags.index')" :current="request()->routeIs('admin.blog-tags.*')" wire:navigate>{{ __('Etiquetas del blog') }}</flux:sidebar.item>
                    @endcan
                    @can('testimonials.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.testimonials.index')" :current="request()->routeIs('admin.testimonials.*')" wire:navigate>{{ __('Testimonios') }}</flux:sidebar.item>
                    @endcan
                </flux:sidebar.group>

                <flux:sidebar.group
                    :heading="__('Academia')"
                    expandable
                    :expanded="request()->routeIs('admin.courses.*', 'admin.course-categories.*', 'admin.instructors.*', 'admin.course-modules.*')"
                    class="grid"
                >
                    @can('courses.view')
                        <flux:sidebar.item icon="book-open-text" :href="route('admin.courses.index')" :current="request()->routeIs('admin.courses.*')" wire:navigate>{{ __('Cursos') }}</flux:sidebar.item>
                    @endcan
                    @can('course-categories.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.course-categories.index')" :current="request()->routeIs('admin.course-categories.*')" wire:navigate>{{ __('Categorías de cursos') }}</flux:sidebar.item>
                    @endcan
                    @can('instructors.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.instructors.index')" :current="request()->routeIs('admin.instructors.*')" wire:navigate>{{ __('Instructores') }}</flux:sidebar.item>
                    @endcan
                    @can('course-modules.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.course-modules.index')" :current="request()->routeIs('admin.course-modules.*')" wire:navigate>{{ __('Módulos') }}</flux:sidebar.item>
                    @endcan
                </flux:sidebar.group>

                <flux:sidebar.group
                    :heading="__('Operaciones')"
                    expandable
                    :expanded="request()->routeIs('admin.enrollments.*', 'admin.payments.*', 'admin.certificates.*', 'admin.contacts.*', 'admin.newsletter.*')"
                    class="grid"
                >
                    @can('enrollments.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.enrollments.index')" :current="request()->routeIs('admin.enrollments.*')" wire:navigate>{{ __('Inscripciones') }}</flux:sidebar.item>
                    @endcan
                    @can('payments.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.payments.index')" :current="request()->routeIs('admin.payments.*')" wire:navigate>{{ __('Pagos') }}</flux:sidebar.item>
                    @endcan
                    @can('certificates.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.certificates.index')" :current="request()->routeIs('admin.certificates.*')" wire:navigate>{{ __('Certificados') }}</flux:sidebar.item>
                    @endcan
                    @can('contacts.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.contacts.index')" :current="request()->routeIs('admin.contacts.*')" wire:navigate>{{ __('Contactos') }}</flux:sidebar.item>
                    @endcan
                    @can('newsletter.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.newsletter.index')" :current="request()->routeIs('admin.newsletter.*')" wire:navigate>{{ __('Boletín') }}</flux:sidebar.item>
                    @endcan
                </flux:sidebar.group>

                <flux:sidebar.group
                    :heading="__('Sitio web')"
                    expandable
                    :expanded="request()->routeIs('admin.site-branding.*', 'admin.footer-*', 'admin.social-links.*', 'admin.home-*', 'admin.legal-pages.*', 'admin.ai-chat.*')"
                    class="grid"
                >
                    @can('site-branding.view')
                        <flux:sidebar.item icon="sparkles" :href="route('admin.site-branding.index')" :current="request()->routeIs('admin.site-branding.*')" wire:navigate>{{ __('Identidad y Hero') }}</flux:sidebar.item>
                    @endcan
                    @can('footer-links.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.footer-link-groups.index')" :current="request()->routeIs('admin.footer-*')" wire:navigate>{{ __('Footer') }}</flux:sidebar.item>
                    @endcan
                    @can('social-links.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.social-links.index')" :current="request()->routeIs('admin.social-links.*')" wire:navigate>{{ __('Redes sociales') }}</flux:sidebar.item>
                    @endcan
                    @can('home-stats.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.home-stats.index')" :current="request()->routeIs('admin.home-*')" wire:navigate>{{ __('Secciones del home') }}</flux:sidebar.item>
                    @endcan
                    @can('legal-pages.view')
                        <flux:sidebar.item icon="book-open-text" :href="route('admin.legal-pages.index')" :current="request()->routeIs('admin.legal-pages.*')" wire:navigate>{{ __('Páginas legales') }}</flux:sidebar.item>
                    @endcan
                    @can('ai-chat.view')
                        <flux:sidebar.item icon="chat-bubble-left-right" :href="route('admin.ai-chat.index')" :current="request()->routeIs('admin.ai-chat.*')" wire:navigate>{{ __('Chat IA') }}</flux:sidebar.item>
                    @endcan
                </flux:sidebar.group>

                <flux:sidebar.group
                    :heading="__('Comercio')"
                    expandable
                    :expanded="request()->routeIs('admin.services.*', 'admin.service-categories.*', 'admin.products.*', 'admin.product-categories.*', 'admin.orders.*')"
                    class="grid"
                >
                    @can('services.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.services.index')" :current="request()->routeIs('admin.services.*')" wire:navigate>{{ __('Servicios') }}</flux:sidebar.item>
                    @endcan
                    @can('service-categories.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.service-categories.index')" :current="request()->routeIs('admin.service-categories.*')" wire:navigate>{{ __('Categorías de servicios') }}</flux:sidebar.item>
                    @endcan
                    @can('products.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.products.index')" :current="request()->routeIs('admin.products.*')" wire:navigate>{{ __('Productos') }}</flux:sidebar.item>
                    @endcan
                    @can('product-categories.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.product-categories.index')" :current="request()->routeIs('admin.product-categories.*')" wire:navigate>{{ __('Categorías de productos') }}</flux:sidebar.item>
                    @endcan
                    @can('orders.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.orders.index')" :current="request()->routeIs('admin.orders.*')" wire:navigate>{{ __('Pedidos') }}</flux:sidebar.item>
                    @endcan
                </flux:sidebar.group>

                <flux:sidebar.group
                    :heading="__('Sistema')"
                    expandable
                    :expanded="request()->routeIs('admin.media.*', 'admin.settings.*', 'admin.storage-settings.*')"
                    class="grid"
                >
                    @can('media.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.media.index')" :current="request()->routeIs('admin.media.*')" wire:navigate>{{ __('Archivos') }}</flux:sidebar.item>
                    @endcan
                    @can('settings.view')
                        <flux:sidebar.item icon="layout-grid" :href="route('admin.settings.index')" :current="request()->routeIs('admin.settings.*')" wire:navigate>{{ __('Configuración') }}</flux:sidebar.item>
                    @endcan
                    @can('storage-settings.view')
                        <flux:sidebar.item icon="server" :href="route('admin.storage-settings.index')" :current="request()->routeIs('admin.storage-settings.*')" wire:navigate>{{ __('Almacenamiento') }}</flux:sidebar.item>
                    @endcan
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repositorio') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentación') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Configuración') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Cerrar sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
