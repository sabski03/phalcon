<div class="card">
    <div class="card-body register-card-body">
        <p class="login-box-msg">Register a new membership</p>
        {{ flash.output() }}
        {{ flashSession.output() }}
        {{ form() }}
        <div class="input-group mb-3">
            {{ form.render('name', ["class":"form-control"]) }}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            {{ form.render('email', ["class":"form-control"]) }}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>


        <div class="input-group mb-3">
            {{ form.render('phone', ["class":"form-control"]) }}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-regular fa-phone"></span>
                </div>
            </div>
        </div>


        <div class="input-group mb-3">
            {{ form.render('address', ["class":"form-control"]) }}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-map-marker-alt"></span>
                </div>
            </div>
        </div>



        <div class="input-group mb-3">
            {{ form.render('password', ["class":"form-control"]) }}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            {{ form.render('confirmPassword', ["class":"form-control"]) }}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="row">

            <!-- /.col -->
            <div class="col-4">
                {{ form.render('csrf', ['value': security.getToken()]) }}

                {{ form.render('signUp') }}
            </div>
            <!-- /.col -->
        </div>
        </form>
        <hr>
        <a href="/" class="text-center">I already have a membership</a>
    </div>
</div>