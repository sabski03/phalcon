<div class="card-body">
        {{ form() }}
        <div class="form-group">
            <label for="name">New Header</label>
            {{ form.render("new_header", ["class": "form-control"]) }}
        </div>
        <div class="form-group">
            <label for="email">New Post</label>
            {{ form.render("new_post", ["class": "form-control"]) }}
        </div>
        <div class="btn-group">
            {{ submit_button('Save', 'class': 'btn btn-success', 'value':'Save') }}
            {{ link_to("/news", 'Cancel', "class": "btn btn-warning") }}
        </div>
            {{ end_form() }}
</div>