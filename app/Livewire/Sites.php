<?php

namespace App\Livewire;

use App\Models\Site;
use Livewire\Component;
use Livewire\WithPagination;

class Sites extends Component
{
    use WithPagination;

    public $name = '';
    public $url = '';
    public $check_interval = 5;
    public $is_active = true;
    
    public $editingId = null;
    public $showModal = false;
    public $deleteConfirmId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'url' => 'required|url|max:255',
        'check_interval' => 'required|integer|min:1|max:1440',
        'is_active' => 'boolean',
    ];

    public function render()
    {
       
        return view('livewire.sites', [
            'sites' => Site::latest()->paginate(10),
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        Site::create([
            'name' => $this->name,
            'url' => $this->url,
            'check_interval' => $this->check_interval,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Site created successfully.');
        $this->resetForm();
        $this->showModal = false;
    }

    public function edit($id)
    {
        $site = Site::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $site->name;
        $this->url = $site->url;
        $this->check_interval = $site->check_interval;
        $this->is_active = $site->is_active;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        $site = Site::findOrFail($this->editingId);
        $site->update([
            'name' => $this->name,
            'url' => $this->url,
            'check_interval' => $this->check_interval,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Site updated successfully.');
        $this->resetForm();
        $this->showModal = false;
    }

    public function confirmDelete($id)
    {
        $this->deleteConfirmId = $id;
    }

    public function delete()
    {
        if ($this->deleteConfirmId) {
            Site::findOrFail($this->deleteConfirmId)->delete();
            session()->flash('message', 'Site deleted successfully.');
            $this->deleteConfirmId = null;
        }
    }

    public function cancelDelete()
    {
        $this->deleteConfirmId = null;
    }

    public function toggleActive($id)
    {
        $site = Site::findOrFail($id);
        $site->update(['is_active' => !$site->is_active]);
        
        session()->flash('message', 'Site status updated.');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->url = '';
        $this->check_interval = 5;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
}
