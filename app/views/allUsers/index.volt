<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                {{ link_to("allUsers/newUser", "Create a User", "class": "btn btn-primary btn-sm") }}
            </div>

            <div class="card-body">
                <table class="table table-hover responsive" id="dataTables" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% for user in users %}
                        <tr>
                            <td>{{ user.id }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.phone }}</td>
                            <td>{{ user.address }}</td>
                            <td>


                                <div class="btn-group" role="group" aria-label="">
                                    {{ link_to("/allUsers/viewUsers/" ~ user.id, '<i class="fas fa-eye btn btn-primary btn-sm"></i>' ) }}
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
