(() => {
  const $alert = document.querySelectorAll(".alert__message");

  if (!$alert.length) {
    return;
  }

  $alert.forEach(lmt => {
    lmt.addEventListener("click", e => {
      e.preventDefault();

      e.target.parentNode.removeChild(e.target);
    });
  });
})();
