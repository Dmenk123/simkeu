$(document).ready(function () {
	baseUrl = $('#base_url').text();
});

function login_proc() {
	$.ajax({
		url: baseUrl + "login/login_proc",
		type: 'POST',
		dataType: "JSON",
		// data: {email: email, password: password},
		data: $('#form_login').serialize(),
		success: function (data) {
			if (data.level == "2") {
				swal("Sukses", data.pesan, "success").then(function() {
					window.location.href = baseUrl + "home";
				});
			}
			else {
				swal("Sukses", data.pesan, "success").then(function() {
					window.location.href = baseUrl + "dashboard_adm";
				});
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			swal("Gagal", 'password / username anda tidak cocok', "error");
		}
	});
}

function forgotPassProc() {
	$("#CssLoader").removeClass('hidden');
	$.ajax({
		url: baseUrl + "login/kirim_token_forgotpass",
		type: 'POST',
		dataType: "JSON",
		data: $('#form_forgot_pass').serialize(),
		success: function (data) {
			swal("Sukses", data.pesan, "success").then(function() {
				$("#CssLoader").addClass('hidden');
				window.location.href = baseUrl + "home";
			});
		},
		error: function (e) {
			$("#CssLoader").addClass('hidden');
			swal("Gagal", 'ERROR : '+ e, "error");
		}
	});
}

function logout_proc() {
	swal({
      	title: "Logout",
      	text: "Apakah Anda Yakin ingin Logout!",
      	icon: "warning",
      	buttons: [
        	'Tidak',
        	'Ya'
      	],
      	dangerMode: true,
    	}).then(function(isConfirm) {
      		if (isConfirm) {
        		$.ajax({
					url: baseUrl + 'login/logout_proc',
					type: 'POST',
					dataType: "JSON",
					success: function (data) {
						swal("Logout", 'Anda berhasil logout', "success").then(function() {
						    window.location.href = baseUrl + 'home';
						});
					}
				});
      		} else {
        		swal("Batal", "Aksi dibatalkan", "error");
     	 	}
		});
}