<?php

namespace App\Http\Livewire\Project\Setting;

use App\Models\Project;
use Illuminate\Support\Str;
use Livewire\Component;

class Factors extends Component

{
    public $project_id;
    public $factors;
    public $name;
    public $ratio='1';
    public $editName;
    public $editedFactor = null;

    public function addNewGroup() {
        $newGroup = [
            'id' => Str::uuid(),
            'name' => $this->name,
            'ratio' => $this->ratio,
        ];

        $project = Project::find($this->project_id);

        $factors = collect($project->setting['factor']);

        $factors->push($newGroup);

        $project->fill([
            'setting->group' => $factors->toArray()
        ]);

        $project->save();

        $this->groups = $factors->toArray();

    }
    public function empty() {
        $this->name = '';
        $this->editedFactor = null;
    }

    public function editName($id) {
        $factor = collect($this->factors)->where('id', $id)->first();
        $this->editedFactor = $id;
        $this->editName = $factor['name'];
        $this->emit('editFactorName');
    }
    public function editNameSubmit() {

        $project = Project::find($this->project_id);

        $factors = collect($project->setting['factor']);
        $factorData = $factors->where('id', $this->editedFactor);
        $factor=$factorData->first();
        $key = $factorData->keys()->first();

        $group = [
            'id' => $this->editedFactor,
            'name' => $this->editName,
            'type' => $factors['type'],
            'config' => $factor['config']
        ];

        $replaced = $factors->replace([$key=>$group]);

        $project->fill([
            'setting->factor' => $replaced
        ]);

        $project->save();

        $this->factors = $project->setting['factor'];

        $this->empty();
    }

    public function deleteRow($groupId) {

        $project = Project::find($this->project_id);

        $factors = collect($project->setting['group'])->where('id', '!=', $groupId);

        $project->fill([
            'setting->group' => $factors
        ]);

        $project->save();

        $this->groups = $project->setting['group'];

    }

    public function render() {
        return view('livewire.project.setting.factors');
    }
}
