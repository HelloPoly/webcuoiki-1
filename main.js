// --------------------------------- INDEX ---------------------------------------

// xử lí navbar
function homejv() {
  $('.btn_side').click(function() {
      $(this).toggleClass("click");
      $('.sidebar').toggleClass("show");
  });
  $('.feat-btn').click(function() {
      $('nav ul .feat-show').toggleClass("show");
      $('nav ul .first').toggleClass("rotate");
  });
  $('.serv-btn').click(function() {
      $('nav ul .serv-show').toggleClass("show1");
      $('nav ul .second').toggleClass("rotate");
  });
  $('nav ul li').click(function() {
      $(this).addClass("active").siblings().removeClass("active");
  });
}

// khi load trang xong gọi handleLoadResult()
let pageUrl = window.location.href
if (pageUrl == 'http://localhost/webcuoiki/index.php') {
  window.onload = () => {
    handleLoadResult()
  }
}

// LOAD USER LIST
function handleLoadResult() {
  const getUsersAPI = "http://localhost/webcuoiki/admin/api/get_users.php";
  fetch(getUsersAPI)
    .then((res) => res.json())
    .then(response => {
      let arr = response.data;
      let tbody = document.querySelector("tbody");
      //Vong lap qua tung item
      arr.forEach((item) => {
        let tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${item.eid}</td>
          <td>${item.fullname}</td>
          <td>${item.phongban}</td>
          <td>${item.level == 2 ? "nhân viên" : "trưởng phòng"}</td>
          <td>
              <button onclick="handleResetPass(${item.eid}, '${item.username}')" type="button" class="btn btn-danger" data-toggle="modal" data-target="#reset-password-confirm">
                <i class="fa fa-key"></i>
              </button>
              <button onclick="showUserInfo(${item.eid})" type="button" class="btn btn-primary" data-toggle="modal" data-target="#show-user-info">
                <i class="fas fa-info-circle"></i>
              </button>
          </td>`;
        tbody.appendChild(tr);
      });
    });

}

// SHOW THÔNG TIN CỦA 1 USER CỤ THỂ
function showUserInfo(id) {
  const getUserAPI = "http://localhost/webcuoiki/admin/api/get_user.php";
  fetch(getUserAPI, {
    method: "POST",
    body: JSON.stringify({ id: id }),
  })
    .then((res) => res.json())
    .then((data) => {
      data = data.data;
      $(".eid").html(data.eid);
      $(".name").html(data.fullname);
      $(".birthday").html(data.birthday);
      $(".gender").html(data.gender);
      $(".department").html(data.phongban);
      if (data.level == 2) {
        $(".role").html("nhân viên");
      } else {
        $(".role").html("trưởng phòng");
      }
    });
}

// RESET PASSWORD CỦA 1 USER VỀ MẶC ĐỊNH
function handleResetPass(id, user) {
  $("#close-button").click(() => {
    $("#reset-button").off("click");
  });

  $("#reset-button").click(function () {
    fetch("http://localhost/webcuoiki/admin/api/reset_default_pass.php", {
      method: "POST",
      headers: {
        "Content-type": "application/json",
      },
      body: JSON.stringify({ id: id, username: user }),
    })
      .then((res) => res.json())
      .then((data) => {
        $("#reset-password-confirm").modal("hide");
      });
  });
}

// XỬ LÍ ADD USER
function handleAddUser() {
  $(".exit").click(() => {
    $("#add-user-form").off("submit");
  });

  $("#add-user-form").submit((e) => {
    e.preventDefault();
    let fullName = $("#full-name").val();
    let userName = $("#user-name").val();
    let phongban = $("#phongban").val();
    let chucvu = $("#chucvu").val();
    let birthday = $("#user-birthday").val();
    let gender = $("#user-gender").val();

    if (Number(birthday.slice(0, 4)) < 1950) {
      $("#add-form-alert").removeClass("d-none");
      $("#add-form-alert").html('Năm sinh phải lớn hơn 1950');
      return
    }

    if (validateAddUserForm(fullName, userName)) {
      $("#add-form-alert").addClass("d-none");
      // gọi api
      const addUserAPI = "http://localhost/webcuoiki/admin/api/add_user.php";
      fetch(addUserAPI, {
        method: "POST",
        headers: {
          "Content-type": "application/json",
        },
        body: JSON.stringify({
          fullname: fullName,
          user: userName,
          phongban: phongban,
          chucvu: chucvu,
          birthday: birthday,
          gender: gender
        }),
      })
        .then((res) => res.json())
        .then(showAddMessage); // show modal thành công hoặc thất bại
    } else {
      $("#add-form-alert").removeClass("d-none");
    }
  });
}

// add xong hiện modal message thành công hoặc thất bại
function showAddMessage(data) {
  if (data.code == 0) {
    $("#mess").html("Thêm nhân viên thành công");
    $("#confirm-add-user").modal("hide");
    $("#add-show-mess").modal("show");
    // load lại user list
    $('tbody').children().remove();
    handleLoadResult()
  } else {
    $("#mess").html(data.error);
    $("#confirm-add-user").modal("hide");
    $("#add-show-mess").modal("show");
  }
}

// XỬ LÍ INPUT
function validateAddUserForm(fullName, userName) {
  const usernameRegex = /^[a-zA-Z0-9_]+$/;
  const fullnameRegex = /^[a-zA-Z0-9_ áàảạãăắằẳặâẩậầấùúủụũưứừựửữỉĩịìíêếềệễểòóọỏõòôổốỗộồơờợởỡ]+$/i;
  if (fullName.match(fullnameRegex) && userName.match(usernameRegex)) {
    return true;
  }
  return false;
}


// --------------------------- CHANGE PASSWORD PAGE ---------------------------------------------
$('#change-password').submit(e => {
  let oldPass = $('#oldPass').val()
  let newPass = $('#newPass').val()
  let newPassAgain = $('#newPassAgain').val()

  if (!validateChangePassForm(oldPass, newPass, newPassAgain)) {
    e.preventDefault()
    $('#change-pass-form-alert').html('Mật khẩu chỉ được chứa chữ cái, số và các kí tự sau: !@#$%^&*')
    $('#change-pass-form-alert').removeClass('d-none')
    return
  } else {
    $('#change-pass-form-alert').addClass('d-none')
  }

  if (newPass != newPassAgain) {
    e.preventDefault()
    $('#change-pass-form-alert').html('Mật khẩu mới không khớp, kiểm tra lại')
    $('#change-pass-form-alert').removeClass('d-none')
    return
  } else {
    $('#change-pass-form-alert').addClass('d-none')
  }

})

function validateChangePassForm(oldPass, newPass, newPassAgain) {
  const passwordRegex = /^[a-zA-Z0-9!@#$%^&*]+$/;
  if (oldPass.match(passwordRegex) && newPass.match(passwordRegex) && newPassAgain.match(passwordRegex)) {
    return true
  }
  return false
}


// ------------------------------- QUẢN LÍ PHÒNG BAN ---------------------------------------------  

if (pageUrl == 'http://localhost/webcuoiki/admin/phongban.php') {
  window.onload = () => {
    handleLoadDepartment()
  }
}

// Load danh sách phòng ban
function handleLoadDepartment() {
  const getDepartmentsAPI = "http://localhost/webcuoiki/admin/api/get_departments.php";
  fetch(getDepartmentsAPI)
    .then(res => res.json())
    .then(response => {
      let arr = response.data;
      let tbody = document.querySelector("tbody");
      //Vong lap qua tung item
      arr.forEach((item) => {
        let tr = document.createElement("tr");
        tr.innerHTML = `
                    <td>${item.ma_so}</td>
                    <td>${item.name}</td>
                    <td>${item.manager}</td>
                    <td>${item.description}</td>
                    <td>
                        <button onclick="handleUpdateDepartment(${item.id}, ${item.ma_so}, '${item.name}', '${item.description}')" type="button" class="btn btn-danger" data-toggle="modal" data-target="#edit-department-dialog">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="showDeparmentInfo(${item.id})" type="button" class="btn btn-primary" data-toggle="modal" data-target="#show-department-info">
                          <i class="fas fa-info-circle"></i>
                        </button>
                    </td>`;
        tbody.appendChild(tr);
      });
    })
}

// show thông tin của 1 phòng ban cụ thể
function showDeparmentInfo(id) {
  const getDeparmentAPI = "http://localhost/webcuoiki/admin/api/get_department.php";
  fetch(getDeparmentAPI, {
    method: "POST",
    body: JSON.stringify({ id: id }),
  })
    .then((res) => res.json())
    .then((data) => {
      data = data.data;
      $(".department-name").html(data.name);
      $(".department-code").html(data.ma_so);
      $(".manager").html(data.manager);
      $(".description").html(data.description);

    });
}

// xử lí thêm phòng ban mới
function handleAddDepartment() {
  $(".exit").click(() => {
    $("#add-department-form").off("submit");
  });

  $("#add-department-form").submit((e) => {
    e.preventDefault();
    let departmentName = $("#department-name").val();
    let departmentCode = $("#department-code").val();
    let desc = $("#desc").val();

    if (departmentCode < 1 || departmentCode == '') {
      $('#add-form-alert').html('Mã phòng ban phải lớn hơn 0')
      $("#add-form-alert").removeClass("d-none");
      return
    }

    if (validateAddDepartmentForm(departmentName, desc)) {
      $("#add-form-alert").addClass("d-none");
      // gọi api
      const addDeparmentAPI = "http://localhost/webcuoiki/admin/api/add_department.php";
      fetch(addDeparmentAPI, {
        method: "POST",
        headers: {
          "Content-type": "application/json",
        },
        body: JSON.stringify({
          name: departmentName,
          code: departmentCode,
          desc: desc,
        }),
      })
        .then((res) => res.json())
        .then(showAddDepartmentMessage); // show modal thành công hoặc thất bại
    } else {
      $("#add-form-alert").removeClass("d-none");
    }
  });
}

function validateAddDepartmentForm(name, desc) {
  const regex = /^[a-zA-Z0-9_ áàảạãăắằẳặâẩậầấùúủụũưứừựửữỉĩịìíêếềệễểòóọỏõòôổốỗộồơờợởỡ]+$/i;
  if (name.match(regex) && desc.match(regex)) {
    return true;
  } else
    return false;
}

// show thông tin thêm thành công hoặc thất bại
function showAddDepartmentMessage(data) {
  if (data.code == 0) {
    $("#mess").html("Thêm phòng ban thành công");
    $("#confirm-add-department").modal("hide");
    $("#add-show-mess").modal("show");
    // load lại user list
    $('tbody').children().remove();
    handleLoadDepartment()
  } else {
    $("#mess").html(data.error);
    $("#confirm-add-department").modal("hide");
    $("#add-show-mess").modal("show");
  }
}

function showEditDepartmentMessage(data) {
  if (data.code == 0) {
    $("#mess").html("CHỉnh sửa phòng ban thành công");
    $("#edit-department-dialog").modal("hide");
    $("#add-show-mess").modal("show");
    // load lại user list
    $('tbody').children().remove();
    handleLoadDepartment()
  } else {
    $("#mess").html(data.error);
    $("#edit-department-dialog").modal("hide");
    $("#add-show-mess").modal("show");
  }
}

// chỉnh sửa thông tin phòng ban
function handleUpdateDepartment(id, code, name, desc){
  console.log(code, name, desc )
  $('#edit-name').val(name)
  $('#edit-code').val(code)
  $('#edit-desc').val(desc)

  $('#edit-department-form').submit(e => {
    e.preventDefault()
    let newName = $('#edit-name').val()
    let newCode = $('#edit-code').val()
    let newDesc = $('#edit-desc').val()

    // kiểm tra mã số có bé hơn 1 ?
    if (newCode < 1 || newCode == '') {
      $('#edit-form-alert').html('Mã phòng ban phải lớn hơn 0')
      $("#edit-form-alert").removeClass("d-none");
      return
    }
    
    // kiểm tra thông tin có thay đổi ?
    if(code == newCode && name == newName && desc == newDesc) {
      $("#edit-form-alert").html("Không có thông tin nào thay đổi");
      $("#edit-form-alert").removeClass("d-none");
      return
    }

    if(validateAddDepartmentForm(newName, newDesc)) {
      $("#edit-form-alert").addClass("d-none");
      // gọi api
      const editDeparmentAPI = "http://localhost/webcuoiki/admin/api/update_department.php";
      fetch(editDeparmentAPI, {
        method: "PATCH",
        headers: {
          "Content-type": "application/json",
        },
        body: JSON.stringify({
          id: id,
          newName: newName,
          newCode: newCode,
          newDesc: newDesc
        }),
      })
        .then((res) => res.json())
        .then(showEditDepartmentMessage) // show modal thành công hoặc thất bại
    }else{
      $("#edit-form-alert").html("Vui lòng điền thông tin đầy đủ, không sử dụng các kí tự như ~,.-+= ...");
      $("#edit-form-alert").removeClass("d-none");
      
    }
  })
}


