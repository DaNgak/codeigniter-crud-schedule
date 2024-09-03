<?= $this->extend('layouts/guest') ?>

<?= $this->section('title') ?>
    Login
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link href="<?= base_url('css/login.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Nested Row within Card Body -->
<div class="row">
    <div class="col">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Welcome to Schedule App</h1>
            </div>
            <form class="user" action="<?= site_url('/loginPost') ?>" method="post">
                <?php if(session()->getFlashdata('message')):?>
                    <div class="alert alert-<?= session()->getFlashdata('message')['type'] ?> alert-dismissible fade show mb-4" role="alert">
                        <strong><?= session()->getFlashdata('message')['title'] ?></strong><br/> <?= session()->getFlashdata('message')['data'] ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif;?>
                <div class="form-group">
                    <input type="email" class="form-control form-control-user" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Password">
                </div>
                <!-- <div class="form-group">
                    <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                    </div>
                </div> -->
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Login
                </button>
            </form>
            <hr>
            <!-- <div class="text-center">
                <a class="small" href="<?= base_url('forgot-password') ?>">Forgot Password?</a>
            </div>
            <div class="text-center">
                <a class="small" href="<?= base_url('register') ?>">Create an Account!</a>
            </div> -->
        </div>
    </div>
</div>
<?= $this->endSection() ?>


