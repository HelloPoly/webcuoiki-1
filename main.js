// --------------------------------- INDEX ---------------------------------------
window.onload = () => {
  const getUsersAPI = "http://localhost/webcuoiki/admin/api/get_users.php";
  fetch(getUsersAPI)
    .then((res) => res.json())
    .then(handleLoadResult);
};

// LOAD USER LIST
function handleLoadResult(response) {
  let arr = response.data;
  let tbody = document.querySelector("tbody");
  //Vong lap qua tung item
  arr.forEach((item) => {
    let tr = document.createElement("tr");
    tr.innerHTML = `<td>${item.eid}</td>
                          <td>${item.fullname}</td>
                          <td>${item.phongban}</td>
                          <td>${
                            item.level == 2 ? "nhân viên" : "trưởng phòng"
                          }</td>
                          <td>
                              <button onclick="handleResetPass(${item.eid}, '${
      item.username
    }')" type="button" class="btn btn-danger" data-toggle="modal" data-target="#reset-password-confirm">
                                <i class="fa fa-key"></i>
                              </button>
                              <button onclick="showUserInfo(${
                                item.eid
                              })" type="button" class="btn btn-primary" data-toggle="modal" data-target="#show-user-info">
                                <i class="fas fa-info-circle"></i>
                              </button>
                      </td>`;
    tbody.appendChild(tr);
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
      $(".name").html(data.fullname);
      $(".department").html(data.phongban);
      if (data.level == 2) {
        $(".user-role").html("nhân viên");
      } else {
        $(".user-role").html("trưởng phòng");
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
    $('.exit').click(() => {
        $("#add-user-form").off('submit')
    })

    $("#add-user-form").submit((e) => {
    e.preventDefault();
    let fullName = $("#full-name").val();
    let userName = $("#user-name").val();
    let phongban = $("#phongban").val();
    let chucvu = $("#chucvu").val();

    if (validateAddUserForm(fullName, userName)) {
      $("#add-form-alert").addClass("d-none");
      // gọi api
      console.log(fullName, userName, phongban, chucvu)
      const addUserAPI = 'http://localhost/webcuoiki/admin/api/add_user.php'
      fetch(addUserAPI,{
          method: "POST",
          headers: {
            "Content-type": "application/json",
          },
          body: JSON.stringify({fullname: fullName, user: userName, phongban: phongban, chucvu: chucvu})
      })
      .then(res => res.json())
      .then(showAddMessage)  // show modal thành công hoặc thất bại
    } else {
      $("#add-form-alert").removeClass("d-none");
    }
  });
}

function showAddMessage(data){
    if(data.code == 0){
        $('#mess').html('Thêm nhân viên thành công')
        $('#confirm-add-user').modal('hide')
        $('#add-show-mess').modal('show')
    }else{
        $('#mess').html(data.error)
        $('#confirm-add-user').modal('hide')
        $('#add-show-mess').modal('show')
    }
}

// XỬ LÍ INPUT 
function validateAddUserForm(fullName, userName) {
  const usernameRegex = /^[a-zA-Z0-9]+$/;
  const fullnameRegex = /^[a-z]([-']?[a-z]+)*( [a-z]([-']?[a-z]+)*)+$/i;
  if (fullName.match(fullnameRegex) && userName.match(usernameRegex)) {
    return true;
  }
  return false;
}
