<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <table class="table table-hover responsive" id="dataTables" width="100%" cellspacing="0">
                        <thead>
                            <tr role="row">
                                <th>Date</th>
                                <th>Operator</th>
                                <th>Reason</th>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        {% for transferred in transfer %}
                            <tr>
                                <td>{{ transferred.created_at }}</td>
                                <td>{{ transferred.operator }}</td>
                                <td>{{ transferred.reason }}</td>
                                <td>{{ transferred.user_id}}</td>
                                <td>{{ transferred.transferUser.name }}</td>
                                <td>{{ transferred.transferUser.phone}}</td>
                                <td>{{ transferred.transferUser.address}}</td>
                                <td>{{ link_to("/allUsers/viewUsers/" ~ transferred.transferUser.id, '<i class="fas fa-eye btn btn-primary btn-sm"></i>' ) }}</td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>