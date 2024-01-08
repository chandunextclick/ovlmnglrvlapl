<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\Tasks\StoreTaskResult;
use App\Models\Task;
use App\Models\TaskResult;

class TaskResultController extends AccountBaseController
{
    //

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.tasks';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('tasks', $this->user->modules));
            return $next($request);
        });
    }

    /**
     *
     * @param StoreTaskResult $request
     * @return void
     */
    public function store(StoreTaskResult $request)
    {
        $this->addPermission = user()->permission('add_task_notes');
        $task = Task::findOrFail($request->taskId);
        $taskUsers = $task->users->pluck('id')->toArray();

        abort_403(!(
            $this->addPermission == 'all'
            || ($this->addPermission == 'added' && $task->added_by == user()->id)
            || ($this->addPermission == 'owned' && in_array(user()->id, $taskUsers))
            || ($this->addPermission == 'added' && (in_array(user()->id, $taskUsers) || $task->added_by == user()->id))
        ));

        $result = new TaskResult();
        $result->result = trim_editor($request->result);
        $result->task_id = $request->taskId;
        $result->user_id = user()->id;
        $result->added_by = user()->id;
        $result->last_updated_by = user()->id;
        $result->save();

        $this->results = TaskResult::where('task_id', $request->taskId)->orderBy('id')->get();
        
        $view = view('tasks.results.show', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = TaskResult::findOrFail($id);
        $this->deleteTaskNotePermission = user()->permission('delete_task_notes');
        abort_403(!($this->deleteTaskNotePermission == 'all' || ($this->deleteTaskNotePermission == 'added' && $note->added_by == user()->id)));

        $result_task_id = $result->task_id;
        $result->delete();
        $this->results = TaskResult::with('task')->where('task_id', $result_task_id)->orderBy('id', 'desc')->get();
        $view = view('tasks.results.show', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->result = TaskResult::with('user', 'task')->findOrFail($id);
        $this->editTaskNotePermission = user()->permission('edit_task_notes');
        abort_403(!($this->editTaskNotePermission == 'all' || ($this->editTaskNotePermission == 'added' && $this->note->added_by == user()->id)));

        return view('tasks.results.edit', $this->data);

    }

    public function update(StoreTaskResult $request, $id)
    {
        $result = TaskResult::findOrFail($id);
        $this->editTaskNotePermission = user()->permission('edit_task_notes');

        abort_403(!($this->editTaskNotePermission == 'all' || ($this->editTaskNotePermission == 'added' && $note->added_by == user()->id)));

        $result->result = trim_editor($request->result);
        $result->save();

        $this->results = TaskResult::with('task')->where('task_id', $result->task_id)->orderBy('id', 'desc')->get();
        $view = view('tasks.results.show', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'view' => $view]);

    }

}
