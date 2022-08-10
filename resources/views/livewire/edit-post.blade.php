<div class="flex item-center justify-center">
    <div>
        <div class="w-4 mr-2 transform hover:text-yellow-500 cursor-pointer hover:scale-110">
            <i class="fa fa-edit" title="Edit post" wire:click="$set('open',true)"></i>
        </div>
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar el post {{ $post->title }}
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-jet-label value="Titulo del post" />
                <x-jet-input wire:model="post.title" type="text" class="w-full" />
                <x-jet-input-error for="post.title" />
            </div>

            <div class="mb-4" wire:ignore>
                <x-jet-label value="Contenido del post" />
                <x-text-area wire:model="post.content" id="editor2" class="w-full"></x-text-area>
                <x-jet-input-error for="post.content" />
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
                <img src="{{ $image->temporaryUrl() }}" alt="">hola1
            @else
                <img src="{{ Storage::url($post->image) }}" alt="">hola2
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('open',false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-danger-button wire:click="save" wire:target="save, image" wire:loading.attr="disabled">
                Actualizar
            </x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
