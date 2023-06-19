<!--  abonentebi -->
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3>PayID Number - {{ users.id }}</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>name</th>
                            <td>{{ users.name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td> {{ users.email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ users.phone }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ users.address }}</td>
                        </tr>
                        <tr>
                            <th>Tasks/Transfered Users</th>

                            <th>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createTask">
                            <i class="fas fa-plus-square"></i>
                            </button>
                            </th>
                            <td>
                                <a href="/allUsers/tasks/{{ id }}">
                                    {{ activeTasks1 }}
                                    {{ unActiveTasks1 }}

                                </a>
                            </td>
                            <div class="modal fade" id="createTask" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create A Task</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">

                                        <form id="myForm" method="post" action="{{ url('allUsers/createTasks/' ~ users.id) }}">
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
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <input type="submit" class="btn btn-primary" value="Create A Task">
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
                              </div>
                            </div>

                            <td>{{ link_to("/transfered/users/" ~ users.id, '<i class="fas fa-random" onclick="return confirm(`tranfser the user cause of a technical problem??`)"></i>') }}</td>
                        </tr>
                        <tr>
                            <th>edit user</th>
                            <td>{{ link_to("/allUsers/edit/" ~ users.id, '<i class="fas fa-cut"></i>', "class": "btn btn-primary btn-sm") }}</td>
                        </tr>
                        <tr>
                            <th>delete user</th>
                            <td>{{ link_to("/allUsers/delete/" ~ users.id, '<i class="fas fa-trash-alt btn btn-danger btn-sm" onclick="return confirm(`are u sure?`)"></i>' ) }}</td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!--      komentaris datoveba       -->

    <div class="col-6">
        <div class="card">
            <div class="card-body">
                {{ form() }}
                <div class="form-group">
                    <label>Comment Type</label>
                    <select name="comment_type" class="form-control">
                        <option value>...</option>
                        <option value="Change User Name">Changed User Name</option>
                        <option value="Change User Email">Changed User Email</option>
                        <option value="Change User Phone">Changed User Phone</option>
                        <option value="Change User Address">Changed User Address</option>
                        <option value="Change User">Changed User</option>
                    </select>
                </div>
                    <div class="form-group">
                        <label>Comment</label>
                        <br>
                        <textarea type="text" name="comment" rows="4" cols="50" placeholder="fill this up"></textarea>
                    </div>

                    <div class="form-group">
                        {{ submit_button('class': 'btn btn-success', 'value':'Add Comment') }}
                    </div>
                {{ end_form() }}
            </div>
        </div>

<!--      angarishis komentari      -->

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Comments</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Comment</th>
                            <th>Creator</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                    {% for comment in comments %}
                        <tr>
                            <td>{{ comment.comment_type }}</td>
                            <td>{{ comment.comment}}</td>
                            <td>{{ comment.name }}</td>
                            <td>{{ comment.created_at }}</td>
                        </tr>
                    {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



