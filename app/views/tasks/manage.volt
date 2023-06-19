<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3> TaskID Number - {{ task.id }}</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    {% for tasks in task %}
                    <tbody>
                    {% set rowClass = (task.closed_at is not empty) ? 'table-success' : 'table-warning' %}
                        <tr class="{{ rowClass }}">
                    {% endfor %}
                            <th>Task Status</th>
                            <td> {{ answer }} </td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ task.name.department}}</td>
                        </tr>
                        <tr>
                            <th>Task</th>
                            <td>{{ task.departments_tasks }}</td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td>{{ task.userID}}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ task.users.name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ task.users.email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ task.users.phone }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ task.users.address }}</td>
                        </tr>
                        <tr>
                            <th>Task Creator</th>
                            <td>{{ task.task_creator }}</td>
                        </tr>
                        <tr>
                            <th>Task Closer</th>
                            <td>{{ task.task_closer }}</td>
                        </tr>
                        <tr>
                            <th>Created Date</th>
                            <td>{{ task.created_at }}</td>
                        </tr>
                        <tr>
                            <th>Updated Date</th>
                            <td>{{ task.updated_at }}</td>
                        </tr>
                        <tr>
                            <th>Closed Date</th>
                            <td>{{ task.closed_at }}</td>
                        </tr>
                        <tr>
                            <th>Created Comment</th>
                            <td>{{ task.created_comment }}</td>
                        </tr>
                        <tr>
                            <th>Updated Comment</th>
                            <td>{{ task.updated_comment }}</td>
                        </tr>
                        <tr>
                            <th>Closed Comment</th>
                            <td>{{ task.closed_comment }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- COMMENTS -->
        <div class="card">
            <div class="card-header">
                <h3> Comments </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Comment</th>
                                <th>Employee</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% for commentTask in commentTasks %}
                            <tr>
                                <th>{{ commentTask.created_at }}</th>
                                <th>{{ commentTask.comment }}</th>
                                <th>{{ commentTask.users.name }}</th>
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
                <!-- PLAN -->
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3>Plan</h3>
            </div>

            <form id="myForm" method="post" action="{{ url('/tasks/manage/' ~ task.id) }}">
                <div class="card-body">
                        <div class="form-group">
                        <label> Should Be Completed Before </label>
                            <div class="input-group-prepend" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"> </i>
                                </div>
                                    <input class="form-control" type="text" value="{{ planned|default('') }}">
                            </div>
                        </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                   <i class="fab fa-buffer"></i>
                                </span>
                            </div>
                            <select class="form-control" name="department" id="department">
                                <option value="nothing">Please Select A Department</option>
                                <option value="1">Call Center</option>
                                <option value="2">Installation Team</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="option_value">Task</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-text-height"></i>
                                </span>
                            </div>
                            <select class="form-control" name="option_value" id="option_value">
                                <option value="">Select An Option Value After Selecting A Department</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea type="text" name="comment" rows="4" cols="50" placeholder="fill this up"></textarea>
                    </div>
                    <div class="modal-footer">

                        <input type="submit" class="btn btn-primary" value="Create A Task">
                    </div>
                </div>
            </form>


            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#department').change(function() {
                        var department = $(this).val();

                        // Empty the option values dropdown
                        $('#option_value').empty().append('<option value="">Please select an option value</option>');

                        if (department) {
                            // Add the appropriate options based on the selected department
                            if (department === '1') {
                                $('#option_value').append('<option value="no internet connection">No Internet Connection</option><option value="internet issues">Internet Issues</option>');
                            } else if (department === '2') {
                                $('#option_value').append('<option value="damaged">Damaged</option><option value="dismantling">Dismantling</option>');
                            }
                        }
                    });

                    $('#option_value').change(function() {
                        var department = $('#department').val();
                        var option_value = $(this).val();

                        if (option_value) {
                            $.ajax({
                                url: '/tasks/get-tasks',
                                type: 'GET',
                                data: {department: department, option_value: option_value},
                                dataType: 'html',

                            });
                        } else {
                            $('#tasks').html('');
                        }
                    });
                });
            </script>
        </div>
    <div class="card-footer">
        <h3>Related Tasks</h3>
        {% for relatedTask in relatedTasks %}
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <tbody>
                        <tr>
                            <th></th>
                            <th>Task</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                        <tr>
                            <th>{{ link_to("/tasks/manage/" ~ relatedTask.id, '<i class="fas fa-eye btn btn-primary btn-sm"></i>' ) }}</th>
                            <th>{{ relatedTask.id }}</th>
                            <th>{{ relatedTask.name.department }} - {{ relatedTask.departments_tasks }}</th>
                            <th>{{ relatedTask.departments_tasks }}</th>
                            <th>{{ relatedTask.created_at }}</th>
                        </tr>
                    </tbody>

                </table>
            </div>
        {% endfor %}
    </div>

<!--         previous tasks! -->

    </div>
</div>
