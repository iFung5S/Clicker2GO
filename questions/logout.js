  var timeout = setTimeout("logout()",1800000);
  function logout() {
    window.location.assign('../login/logout.php?TIMEOUT');
  }

