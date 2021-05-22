<?php

define("db_server", "localhost");
define("db_user", "question_admin");
define("db_password", "**********");
define("db", "question_project");

try {
    $db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
} catch (PDOexception $e) {
    echo $e->getMessage();
    exit;
}


?>
<aside class="side-panel">
    <div class="side-space" id="create-space">
        <a id="create-space-btn" href="javascript:void(0)">+ Create Space</a>
    </div>
    <?php
    $spaces = $db->query("SELECT space_id,space_name,space_logo FROM spaces WHERE space_status = '1' ORDER BY space_id DESC", PDO::FETCH_ASSOC);
    if ($spaces->rowCount()) {
        foreach ($spaces as $space) {
            $isActive = $space['space_id'] == $_GET['cat_id'] ? "active" : "";
            echo '<div class="side-space" data-id="' . $space['space_id'] . '">
                      <img class="mini-img" src="/images/' . $space['space_logo'] . '" alt="mini-space-banner" />
                      <a class="' . $isActive . '" href="/answers/' . $space['space_id'] . '">' . $space['space_name'] . '</a>
                      </div>';
        }
    }
    ?>
</aside>
<script>
	$(document).ready(function() {
		$('#create-space-btn').click(function() {
			Swal.fire({
				title: 'Create a Space',
				input: 'text',
				inputAttributes: {
					autocapitalize: 'on'
				},
				showCancelButton: true,
				confirmButtonText: 'Create',
				inputValidator: (value) => {
					if (!value) {
						return 'You need to write something!'
					}
				}
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						type: "POST",
						url: "/action/create-space",
						data: "user_id=<?= $_SESSION['user_id'] ?>&space_name=" + result.value,
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
	});
</script>