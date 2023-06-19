<div class="card">
    <div class="card-header">
        <h4>User: {{users.name}}</h4>
    </div>
    <div class="card-body">
        {{ form() }}
        <div class="form-group">
            <label for="name">Full Name</label>
            {{ form.render("name", ["class": "form-control"]) }}
        </div>
        <div class="form-group">
            <label for="email">E-Mail</label>
            {{ form.render("email", ["class": "form-control"]) }}
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            {{ form.render("phone", ["class": "form-control"]) }}
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            {{ form.render("address", ["class": "form-control"]) }}
        </div>
        <div class="btn-group">
            {{ submit_button('Save', 'class': 'btn btn-success', 'value':'Save') }}
            {{ link_to("/allUsers", 'Cancel', "class": "btn btn-warning") }}
        </div>
            {{ end_form() }}
    </div>
</div>

