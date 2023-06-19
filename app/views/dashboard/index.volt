<div class="row">
    <div class="col-sm-4 col-md-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ count }}</h3>
                <p>Active Users</p>
                {{ link_to("/allUsers" ~ user.id, '<i class="fas fa-arrow-circle-right"></i>', "class": "btn btn-primary btn-sm") }}
            </div>
        </div>
    </div>
</div>