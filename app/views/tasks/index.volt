<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Tasks
            </div>

            <div class="card-body">
                <table class="table table-hover responsive" id="dataTables" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Closed Date</th>
                            <th>Task</th>
                            <th>Closed By</th>
                            <th>Created Comment</th>
                            <th>Closed Comment</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {% for tasks in task %}
                            {% set rowClass = (tasks.closed_at is not empty) ? 'table-success' : 'table-warning' %}
                            <tr class="{{ rowClass }} odd">
                                <td>{{ tasks.id }}</td>
                                <td>{{ tasks.created_at }}</td>
                                <td>{{ tasks.closed_at }}</td>
                                <td>{{ tasks.name.department }} - {{ tasks.departments_tasks}}</td>
                                <td>{{ tasks.task_closer }}</td>
                                <td>{{ tasks.created_comment }}</td>
                                <td>{{ tasks.closed_comment }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="">
                                        {{ link_to("/tasks/manage/" ~ tasks.id , '<i class="fas fa-eye btn btn-primary btn-sm"></i>' ) }}
                                    </div>
                                </td>
                            </tr>
                        {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>