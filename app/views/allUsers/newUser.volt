<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Create a User</h4>
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
                    <label for="phone">Phone</label>
                    {{ form.render("phone", ["class": "form-control"]) }}
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    {{ form.render("address", ["class": "form-control"]) }}
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    {{ form.render("password", ["class": "form-control"]) }}
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    {{ form.render("confirmPassword", ["class": "form-control"]) }}
                </div>

                {{ submit_button('Save', 'class': 'btn btn-success', 'value':'Save') }}
                {{ end_form() }}
            </div>
        </div>
    </div>
</div>
