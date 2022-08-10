<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPost extends Component
{
    use WithFileUploads;
    public $post, $image, $identificador;
    public $open = false;

    protected $rules = [
        'post.title' => 'required',
        'post.content' => 'required',
    ];


    public function mount(Post $post)
    {
        $this->identificador = rand();
        $this->post = $post;
    }

    public function save()
    {
        $this->validate();
        if ($this->image) {
            Storage::delete([$this->post->image]);
            $this->post->image = $this->image->store('posts');
        }
        $this->post->save();
        $this->reset(['open']);
        $this->emitTo('show-posts', 'render');
        $this->emit('alert', 'El post se actualizo de manera correcta');
    }

    public function render()
    {
        return view('livewire.edit-post');
    }
}
