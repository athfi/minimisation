<?php

namespace App\Http\Livewire\Project\Setting;

use App\Models\Project;
use Illuminate\Support\Str;
use Livewire\Component;



class Groups extends Component
{
    public $project_id;
    public $groups;
    public $name;
    public $ratio='1';
    public $editname;
    public $editratio;
    public $editedRow = null;
    public $showAddGroup = false;

    public function addNewGroup() {
        $newGroup = [
          'id' => Str::uuid(),
          'name' => $this->name,
          'ratio' => $this->ratio,
        ];

        $project = Project::find($this->project_id);

        $groups = collect($project->setting['group']);

        $groups->push($newGroup);

        $project->fill([
            'setting->group' => $groups->toArray()
        ]);

        $project->save();

        $this->groups = $groups->toArray();

    }
    public function empty() {
        $this->name = '';
        $this->ratio = '1';
        $this->editedRow = null;
    }

    public function editRow($id) {
        $group = collect($this->groups)->where('id', $id)->first();
        $this->editedRow = $id;
        $this->editname = $group['name'];
        $this->editratio = $group['ratio'];
        $this->emit('editGroup');
    }
    public function editSubmit() {

        $project = Project::find($this->project_id);

        $groups = collect($project->setting['group']);
        $key = $groups->where('id', $this->editedRow)->keys()->first();

        $group = [
            'id' => $this->editedRow,
            'name' => $this->editname,
            'ratio' => $this->editratio
        ];

        $replaced = $groups->replace([$key=>$group]);

        $project->fill([
            'setting->group' => $replaced
        ]);

        $project->save();

        $this->groups = $project->setting['group'];

        $this->empty();
    }

    public function deleteRow($groupId) {

        $project = Project::find($this->project_id);

        $groups = collect($project->setting['group'])->where('id', '!=', $groupId);

        $project->fill([
            'setting->group' => $groups
        ]);

        $project->save();

        $this->groups = $project->setting['group'];

    }

    public function render() {
        return view('livewire.project.setting.groups');
    }
}







//class Groups extends Component
//{
//    public $project_id, $groups=[];
//
//    protected $listeners = ['saveUpdate' => 'saveUpdate'];
//
//    public function render()
//    {
//        return view('livewire.project.setting.groups');
//    }
//
//    public function removeGroup($groupId)
//    {
//
//        $this->groups = collect($this->groups)->where('id', '!=', $groupId);
//
//    }
//
//    public function saveUpdate ($group)
//    {
//        $project = Project::find($this->project_id);
//
//        $groups = collect($project->setting['group']);
//        $key = $groups->where('id', $group['id'])->keys()->first();
//
//        $replaced = $groups->replace([$key=>$group]);
//
//        $project->fill([
//            'setting->group' => $replaced
//        ]);
//
//        $project->save();
//
//        $project = Project::find($project_id)->refresh();
//
//        $this->groups = collect($project->setting['group']);
//
////        $this->emitUp('saveUpdate');
//
//    }
//
//}
