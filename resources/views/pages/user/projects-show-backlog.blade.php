@extends('layouts.project')

@section('__content')
  <h5>Sprint</h5>
  <div class="backlog-container bg-light">
    <button>Start Sprint</button>
  </div>
  <h5>Backlog</h5>
  <div class="backlog-container bg-light">
    @foreach ($tasks as $task)
    @endforeach
    <button class="btn btn-light" type="button" data-toggle='modal' data-target='#createTaskModal'>
      <i class="fas fa-plus mr-1"></i>
      Add Task
    </button>
  </div>

  <div id="createTaskModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createTaskModalLabel">Create a new task</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('tasks.store') }}" method="POST" id="form-create-task">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <input type="hidden" name="project_code" value="{{ $project->code }}">
            <div class="form-group">
              <label for='task_type_id'>Task Type</label>
              <select name='task_type_id' id='task_type_id' class='form-control'>
                @foreach ($taskTypes as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for='title'>Title</label>
              <input type='text' name='title' id='title' class='form-control'>
            </div>
            <div class="form-group">
              <label for='description'>Description</label>
              <textarea name='description' id='description' class='form-control description'></textarea>
            </div>
            <div class="form-group">
              <label for='deadline'>Deadline</label>
              <input type='date' name='deadline' id='deadline' class='form-control'>
            </div>
            <div class="form-group">
              <label for='assigned_to'>Assigned to</label>
              <select name='assigned_to[]' id='assigned_to' class='form-control my-1'>
                @foreach ($project->members as $member)
                  <option value="{{ $member->id }}">{{ $member->name }}</option>
                @endforeach
              </select>
              <button class="btn btn-light w-100 mt-2 text-left" type="button" id="btn-add-assignee">
                <i class="fas fa-plus mr-1"></i>
                Add Assignee
              </button>
            </div>
            <div class="form-group">
              <label for='label'>Label</label>
              <input type='text' name='label' id='label' class='form-control'>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" form="form-create-task">Create</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('__scripts')
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script>
    $(function() {
      $('#btn-add-assignee').on('click', function () {
        let members = @json($project->members);
        let selectedMembers = $('select[name="assigned_to[]"]').map(function () {
          return this.value;
        }).get();

        let notAssignedMembers = members.filter(member => !selectedMembers.includes(member.id.toString()));

        if (notAssignedMembers.length > 0) {
          let element = `<select name='assigned_to[]' id='assigned_to' class='form-control my-1'>`;
          
          notAssignedMembers.forEach(member => {
            element += `<option value="${member.id}">${member.name}</option>`
          });
          element += `</select>`;
          
          $(element).insertBefore(this);
        }
      })

      $('.description').summernote({
        minheight: 200,
        toolbar: [
          ['control', ['undo', 'redo']],
          ['style', ['bold', 'italic', 'underline', 'strikethrough']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['paragraph', ['ul', 'ol', 'paragraph']],
          ['insert', ['link', 'unlink', 'picture']],
        ]
      });
    })
  </script>
@endsection