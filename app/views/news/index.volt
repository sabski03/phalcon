<div class="mb-3">
    {{ link_to("/news/addPost" ~ user.id, 'Add Post', "class": "btn btn-primary btn-sm") }}
</div>
{% for new in news %}
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"> {{ new.new_header}} </h4>
                </div>
                <div class="collapse show">
                    <div class="card-body">
                        <p>{{ new.new_post }}</p>
                    </div>
                    <div class="row">
                        <div class="col">
                            <footer class="blockquote-footer">{{ new.username.name }} {{ new.created_at }}</footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endfor %}
