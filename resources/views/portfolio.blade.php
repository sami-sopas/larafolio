  <x-main-layout>
      <!-- Hero -->
      <div class="relative bg-white overflow-hidden bg-gray-800">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-gray-800 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-gray-800 transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100"></polygon>
                </svg>

                <!-- livewire component BARRA DE NAVEGACION -->
                <livewire:navigation.navigation />
                <!-- end livewire component -->

                <!-- livewire component TITULO/DESCRICION -->
                <livewire:hero.info />

            </div>
        </div>

        <!-- livewire component IMAGEN -->
        <livewire:hero.image />
    </div>

    <!-- Projects -->
    <div class="bg-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- livewire component PROYECTOS -->
s           <livewire:project.project />
            <!-- end livewire component -->
        </div>
    </div>

    <!-- Footer -->
    <section class="bg-gray-800">
        <div class="flex justify-center pt-10">
            <h2 class="text-2xl font-extrabold text-gray-200">{{ __('contact me') }}</h2>
        </div>
        <div class="max-w-screen-xl px-4 py-3 mx-auto space-y-8 overflow-hidden sm:px-6 lg:px-8">
            <nav class="flex flex-wrap justify-center -mx-5 -my-2">
                <!-- livewire component CONTACT (CORREO) -->
                <livewire:contact.contact />
            </nav>

            <!-- livewire component SOCIAL LINKS -->
            <livewire:contact.social-link />

            <!-- livewire component FOOTER LINK -->
            <livewire:navigation.footer-link />
        </div>
    </section>

  </x-main-layout>
