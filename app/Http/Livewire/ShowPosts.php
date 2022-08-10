<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ShowPosts extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $post, $image, $identificador;
    public $sort = "id";
    public $direction = "desc";
    public $open_edit = false;
    public $buscador = '';
    protected $rules = [
        'post.title' => 'required',
        'post.content' => 'required',
    ];
    public $cant = '10';
    public $readyToLoad = false;

    protected $queryString = [
        'cant' => ['except' => '10'],
        'buscador' => ['except' => ''],
        'sort' => ['except' => 'id'],
        'direction' => ['except' => 'desc']
    ];

    // protected $listeners = ['render' => 'render'];
    protected $listeners = ['render', 'delete'];

    public function mount()
    {
        $this->identificador = rand();
        $this->post = new Post();
    }


    public function render()
    {
        if ($this->readyToLoad) {
            $posts = Post::where('title', 'like', '%' . $this->buscador . '%')
                ->orwhere('content', 'like', '%' . $this->buscador . '%')
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->cant);
        } else {
            $posts = [];
        }
        return view('livewire.show-posts', compact('posts'));
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'desc';
        }
    }

    public function update()
    {
        $this->validate();
        if ($this->image) {
            Storage::delete([$this->post->image]);
            $this->post->image = $this->image->store('posts');
        }
        $this->post->save();
        $this->reset(['open_edit']);
        $this->emit('alert', 'El post se actualizo de manera correcta');
    }

    public function edit(Post $post)
    {
        $this->post = $post;
        $this->open_edit = true;
    }

    public function updatingBuscador()
    {
        $this->resetPage();
    }

    public function loadsPosts()
    {
        $this->readyToLoad = true;
    }

    function delete(Post $post)
    {
        $post->delete();
    }
}
