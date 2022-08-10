<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;


class CreatePost extends Component
{
    use WithFileUploads;

    public $open = false;
    public $title, $content, $image, $identificador;
    protected $rules = [
        'title' => 'required|max:10',
        'content' => 'required',
        'image' => 'required|image|max:2048'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount()
    {
        $this->identificador = rand();
    }

    public function save()
    {

        $this->validate();

        $image = $this->image->store('posts');

        Post::create([
            'title' => $this->title,
            'content' => $this->content,
            'image' => $image
        ]);

        $this->reset(['open', 'title', 'content', 'image']);
        $this->identificador = rand();
        // $this->emit('render');
        $this->emitTo('show-posts', 'render');
        $this->emit('alert', 'El post se creo de manera correcta');
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
