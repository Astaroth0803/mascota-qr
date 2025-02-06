<form action="{{ route('pet.store') }}" method="POST" class="max-w-sm mx-auto" enctype="multipart/form-data">
    @csrf
    <div id="formulario_mascotas">
    <div class="mb-5">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('nombre') }}" placeholder="Nombre de la mascota" />
    </div>

    <div class="mb-5">
        <label for="especie" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Especie</label>
        <input type="text" name="especie" id="especie" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('especie') }}" placeholder="Especie de la mascota" />
    </div>

    <div class="mb-5">
        <label for="raza" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Raza</label>
        <input type="text" name="raza" id="raza" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('raza') }}" placeholder="Raza de la mascota" />
    </div>

    <div class="mb-5">
        <label for="edad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Edad</label>
        <input type="text" name="edad" id="edad" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('edad') }}" placeholder="Edad de la mascota" />
    </div>

    <div class="mb-5">
        <label for="sexo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sexo</label>
        <input type="text" name="sexo" id="sexo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('sexo') }}" placeholder="Sexo de la mascota" />
    </div>
    
    <div id="drop_zone" class="flex items-center justify-center w-full">
            @csrf
            <div class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-full">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Arrastra y suelta el archivo aquí</span> o haz clic para seleccionarlo</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PDF ONLY (MAX. 4MB)</p>
                    </div>
                    <input id="dropzone-file" name="vaccine_file" type="file" accept="application/pdf" />
                </label>
            </div>
            <br>
            <br>
    <button id="btm_enviar_form" type="submit">Registrar Mascota</button>
</form>
</div>