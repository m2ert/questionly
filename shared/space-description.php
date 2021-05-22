<?php
$space_detail = $db->query("SELECT space_name,space_logo FROM spaces WHERE space_id = '{$_GET['cat_id']}'")->fetch(PDO::FETCH_ASSOC);
$follower_count = $db->query("SELECT COUNT(space_id) AS total FROM spaces_followers WHERE space_id = '{$_GET['cat_id']}'")->fetch(PDO::FETCH_ASSOC);
$is_following = $db->query("SELECT COUNT(space_id) AS total FROM spaces_followers WHERE follower_id = '1' AND space_id = '{$_GET['cat_id']}'")->fetch(PDO::FETCH_ASSOC);
?>
<div class="space-description">
    <img src="images/<?= $space_detail['space_logo'] ?>" alt="space-banner-main" class="space-img" />
    <div class="space-info">
        <div class="space-title">
            <h2 class="title"><?= $space_detail['space_name'] ?></h2>
        </div>
        <div class="space-follow-button">
            <?php
            if ($is_following['total'] > 0) {
                echo '<button class="follow-btn btn" id="unfollow-space">
                Unfollow <span id="follow-count">(' . $follower_count['total'] . ')</span>
                </button>';
            } else {
                echo '<button class="follow-btn btn" id="follow-space">
                Follow <span id="follow-count">(' . $follower_count['total'] . ')</span>
                </button>';
            }
            ?>

            <!-- click -> unfollow -->
        </div>
    </div>
    <?php
    if ($route == "questions") {
    ?>
        <div class="add-question" id="add-question">
            <button class="question-btn btn">Add question</button>
        </div>
    <?php
    }
    ?>
</div>
<script>
    $(function() {
        $('.question-btn').click(function() {
            Swal.fire({
                title: 'Add Question',
                input: 'textarea',
                inputAttributes: {
                    autocapitalize: 'on'
                },
                showCancelButton: true,
                confirmButtonText: 'Add',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write something!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "/action/ask-a-question",
                        data: "user_id=<?= $_SESSION['user_id'] ?>&category_id=<?= $_GET['cat_id'] ?>&question=" + result.value,
                        success: function(r) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })
                            Toast.fire({
                                icon: r.outcome ? 'success' : 'error',
                                title: r.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            }).then((result) => {
                                location.reload();
                            })
                        }
                    });
                }
            })
        });
        $('#unfollow-space').click(function() {
            let cat = "<?= $_GET['cat_id'] ?>";
            $.ajax({
                type: "POST",
                url: "/action/unfollow",
                data: "user_id=<?= $_SESSION['user_id'] ?>&category_id=" + cat,
                success: function(r) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: r.outcome ? 'success' : 'error',
                        title: r.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    }).then((result) => {
                        location.reload();
                    })
                }
            });
        });
        $('#follow-space').click(function() {
            let cat = "<?= $_GET['cat_id'] ?>";
            $.ajax({
                type: "POST",
                url: "/action/follow",
                data: "user_id=<?= $_SESSION['user_id'] ?>&category_id=" + cat,
                success: function(r) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: r.outcome ? 'success' : 'error',
                        title: r.message
                    }).then((result) => {
                        location.reload();
                    })
                }
            });
        });
    });
</script>