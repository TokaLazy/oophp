(function () {
  var $alert = document.querySelectorAll('.alert__message')
  if (!$alert.length) return

  $alert.forEach(function (lmt) {
    lmt.addEventListener('click', function (e) {
      e.preventDefault()

      e.target.parentNode.removeChild(e.target)
    })
  })
})()
