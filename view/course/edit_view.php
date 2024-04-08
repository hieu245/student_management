<?php
if (!defined('ROOT_PATH')) {
    die('Can not access');
}
$titlePage = "BTEC - Create New Courses";
$errorUpdate = $_SESSION['error_update_course'] ?? null;
?>
<?php require 'view/partials/header_view.php'; ?>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Create New Courses
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <a class="btn btn-primary btn-lg" href="index.php?c=course">Back To List</a>
        <div class="card mt-3">
            <div class="card-header bg-primary">
                <h5 class="card-title text-white mb-0">Create Courses</h5>
            </div>
            <div class="card-body">
                <form method="post" action="index.php?c=course&m=handle-edit&id=<?= $info['id']; ?>">
                    <div class="row">

                        <div class="col-sm-12 col-md-6">
                            <div class="form-group mb-3">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" value="<?= $info['name']; ?>" ?>
                                <?php if (!empty($errorUpdate['name'])) : ?>
                                    <span class="text-danger"><?= $errorUpdate['name']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group mb-3">
                                <label>Department</label>
                                <select class="form-control" name="department_id"> <!--Dang can giai thich-->

                                <option value="<?= htmlspecialchars($info['department_id']) ?>">

                                    <?= $departmentName[$info['department_id']] ?? '' ?>
                                    </option>
                                    <?php foreach ($department as $item) : ?>
                                        <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                    <?php endforeach; ?>

                                </select>

                                <?php if (!empty($errorUpdate['department_id'])) : ?>
                                    <span class="text-danger"><?= $errorUpdate['department_id']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group mb-3">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="1" <?= $info['status'] == 1 ? 'selected' : null; ?>> Active</option>
                                    <option value="0" <?= $info['status'] == 0 ? 'selected' : null; ?>> Deactive</option>
                                </select>
                            </div>

                            <div>
                                <button class="btn btn-primary" type="submit" name="btnSave">
                                    Save
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <img width="70%" src="https://storage.googleapis.com/bukas-website-v3-prd/website_v3/images/Bukas.ph_Choosing_Course_and_College.original.png" alt="BTEC">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'view/partials/footer_view.php'; ?>