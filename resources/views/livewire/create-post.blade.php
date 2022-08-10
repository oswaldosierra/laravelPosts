<div>
    <x-jet-danger-button wire:click="$set('open',true)" class="shadow-md">
        New Post
    </x-jet-danger-button>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear Nuevo Post
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-jet-label value="Titulo del post" />
                <x-jet-input wire:model="title" type="text" class="w-full" />
                {{-- <x-jet-input wire:model.defer="title" type="text" class="w-full" /> --}}
                <x-jet-input-error for="title" />
            </div>

            <div class="mb-4" wire:ignore>
                <x-jet-label value="Contenido del post" />
                <x-text-area wire:model="content" id="editor" class="w-full"></x-text-area>
                {{-- <x-text-area wire:model.defer="content" class="w-full"></x-text-area> --}}
                <x-jet-input-error for="content" />
            </div>

            <div class="mb-4">
                <input type="file" wire:model="image" id="{{ $identificador }}">
                <x-jet-input-error for="image" />
            </div>

            {{-- Alerta de carga de imagen --}}
            <div wire:loading wire:target="image"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Imagen Cargando!</strong>
                <span class="block sm:inline">Espere un momento hasta que la imagen cargue...</span>
            </div>

            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" alt="">
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('open',false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-danger-button wire:click="save" wire:target="save, image" wire:loading.attr="disabled">
                Crear Post
            </x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>
    @push('js')
        <script>
            ClassicEditor
                .create(document.querySelector('#editor'))
                .then(function(editor) {
                    editor.model.document.on('change:data', () => {
                        @this.set('content', editor.getData());
                    })
                })
                .catch(error => {
                    console.error(error);
                });
        </script>
    @endpush
</div>
