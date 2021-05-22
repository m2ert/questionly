$(document).ready(function () {
	$('#signup-form').validate({
		rules: {
			signup_name: {
				required: true,
				normalizer: function (value) {
					return $.trim(value);
				}
			},
			signup_email: {
				required: true,
				email: true,
				normalizer: function (value) {
					return $.trim(value);
				}
			},
			signup_password: {
				required: true,
				minlength: 8,
				maxlength: 15,
				normalizer: function (value) {
					return $.trim(value);
				}
			}
		},
		messages: {
			signup_name: "Please enter your preferred name",
			signup_email: "Please enter your e-mail address",
			signup_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 8 characters long",
				maxlength: "Your password must be at most 15 characters long"
			},
		},
		errorClass: "text-danger",
		successClass: "text-success",
		highlight: function (element, errorClass) {
			$(element).removeClass(errorClass)
		},
		unhighlight: function (element, errorClass) {
			$(element).removeClass(errorClass)
		},
		errorPlacement: function (error, element) {
			error.insertAfter(element)
		},
		submitHandler: function (form) {
			$.ajax({
				type: "POST",
				url: "action/signup",
				data: $('#signup-form').serialize(),
				beforeSend: function () {
					Swal.showLoading();
				},
				success: function (r) {
					Swal.fire({
						icon: r.outcome ? 'success' : 'error',
						title: 'Information',
						text: r.message,
						footer: 'Questionly &copy; 2021',
						showConfirmButton: false,
						timer: 2500
					})
					$('#signup-form')[0].reset();
				}
			});
		}
	});
	$('#login-form').validate({
		rules: {
			login_email: {
				required: true,
				email: true,
				normalizer: function (value) {
					return $.trim(value);
				}
			},
			login_password: {
				required: true,
				minlength: 8,
				maxlength: 15,
				normalizer: function (value) {
					return $.trim(value);
				}
			}
		},
		messages: {
			login_email: "Please enter your e-mail address",
			login_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 8 characters long",
				maxlength: "Your password must be at most 15 characters long"
			},
		},
		errorClass: "text-danger",
		successClass: "text-success",
		highlight: function (element, errorClass) {
			$(element).removeClass(errorClass)
		},
		unhighlight: function (element, errorClass) {
			$(element).removeClass(errorClass)
		},
		errorPlacement: function (error, element) {
			error.insertAfter(element)
		},
		submitHandler: function (form) {
			$.ajax({
				type: "POST",
				url: "action/login",
				data: $('#login-form').serialize(),
				beforeSend: function () {
					Swal.showLoading();
				},
				success: function (r) {
					Swal.fire({
						icon: r.outcome ? 'success' : 'error',
						title: 'Information',
						text: r.message,
						footer: 'Questionly &copy; 2021',
						showConfirmButton: false,
						timer: 2500
					})
					$('#login-form')[0].reset();
					if(r.outcome){
						window.location.href = r.link;
					}
				}
			});
		}
	});
});